<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Publisher; 
use App\Models\Book; 
use Illuminate\Support\Str;
use App\Traits\Trackable;

class GoogleBookSearch extends Component
{
   use Trackable;
   
    public $searchTerm = '';
    public $results = [];
    public $isLoading = false;
    public $searchPerformed = false;
    
    protected $rules = [
        'searchTerm' => 'required|min:3',
    ];

    /**
     * Mapeia os dados da resposta da Google Books API para a estrutura do Model Book.
     * @param array $item
     * @return array
     */
    private function mapGoogleBook(array $item)
    {
        $volumeInfo = $item['volumeInfo'];
        $industryIdentifiers = $volumeInfo['industryIdentifiers'] ?? [];

        // Extrai ISBN-13
        $isbn13 = collect($industryIdentifiers)
            ->where('type', 'ISBN_13')
            ->first();

        $description = $volumeInfo['description'] ?? 'No description available.';
        
        // Limita a descrição para evitar overflow na base de dados (exemplo)
        $bibliography = mb_substr($description, 0, 1000) . (mb_strlen($description) > 1000 ? '...' : '');

        return [
            'title' => $volumeInfo['title'] ?? 'N/A',
            'isbn' => $isbn13['identifier'] ?? ($industryIdentifiers[0]['identifier'] ?? '-'),
            'year' => substr($volumeInfo['publishedDate'] ?? 'N/A', 0, 4),
            'price' => null, 
            'publisher_name' => $volumeInfo['publisher'] ?? 'Unknown',
            'bibliography' => $bibliography,
            'cover_image' => $volumeInfo['imageLinks']['thumbnail'] ?? null // URL da imagem
        ];
    }

    /**
     * Realiza a pesquisa na Google Books API.
     */
    public function search()
    {
        $this->validate();

        $this->isLoading = true;
        $this->results = [];
        $this->searchPerformed = false;

        $query = urlencode($this->searchTerm);
        $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=10";

        try {
            $response = Http::get($url)->json();

            if (isset($response['items'])) {
                foreach ($response['items'] as $item) {
                    $this->results[] = $this->mapGoogleBook($item);
                }
            }
            $this->searchPerformed = true;

        } catch (\Exception $e) {
            session()->flash('error', 'Error connecting to Google Books API: ' . $e->getMessage());
        }

        $this->isLoading = false;
    }

    /**
     * Salva o livro na base de dados local, tratando a Editora e a Criptografia.
     * @param array $bookData Dados mapeados do Google Books.
     */
    public function importBook(array $bookData)
    {
        // 1. Validar dados essenciais
        if (empty($bookData['title']) || empty($bookData['isbn'])) {
            session()->flash('error', 'Cannot import: Title or ISBN are missing.');
            return;
        }

        try {
            // 2. Encontrar ou Criar a Editora (Publisher)
            $publisherName = $bookData['publisher_name'] ?? 'Unknown';

            // Adicionar um valor para a coluna 'key'
            $publisher = Publisher::firstOrCreate(
                ['name' => $publisherName],
                [
                    'logo' => null,
                    'key' => (string) Str::uuid(), // <--  Gera um ID único para a coluna 'key'
                ] 
            );

            // 3. Preparar os dados para o Model Book
            $dataToSave = [
                'title' => $bookData['title'],
                'isbn' => $bookData['isbn'],
                'publisher_id' => $publisher->id, 
                'year' => $bookData['year'],
                'price' => $bookData['price'],
                'bibliography' => $bookData['bibliography'],
                'cover_image' => $bookData['cover_image'],
            ];

            // 4. Criar o Livro
           $book= Book::create($dataToSave);

            $this->logAudit(
            'GoogleBooks', 
            $book->id, 
            "Imported book via API: '{$bookData['title']}' with ISBN {$bookData['isbn']}"
        );
            
            // 5. Feedback
            session()->flash('success', "Book '{$bookData['title']}' imported and saved successfully! Publisher '{$publisherName}' checked/created.");

            // Opcional: Remover o livro dos resultados para evitar dupla importação
            $this->results = collect($this->results)->reject(function ($item) use ($bookData) {
                return $item['isbn'] === $bookData['isbn'];
            })->toArray();

        } catch (\Exception $e) {
            // Se o erro ainda for de SQL, mostre a mensagem completa
            session()->flash('error', 'Error saving the book: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.google-book-search');
         
    }
}