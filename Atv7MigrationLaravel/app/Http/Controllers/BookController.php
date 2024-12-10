<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Formulário com input de ID
    public function createWithId()
    {
        return view('books.create-id');
    }

    // Salvar livro com input de ID
    public function storeWithId(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'publisher_id' => 'required|exists:publishers,id',
        'author_id' => 'required|exists:authors,id',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação da imagem
    ]);

    $data = $request->all();

    // Verifica se há uma imagem enviada
    if ($request->hasFile('cover_image')) {
        // Armazena a imagem na pasta 'covers' dentro de 'storage/app/public'
        $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
    } else {
        // Definindo a imagem padrão
        $data['cover_image'] = 'covers/default_cover.jpg'; // Imagem padrão
    }

    // Cria o livro no banco de dados
    Book::create($data);

    return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
}

    // Formulário com input select
    public function createWithSelect()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    // Salvar livro com input select
    public function storeWithSelect(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'publisher_id' => 'required|exists:publishers,id',
        'author_id' => 'required|exists:authors,id',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação da imagem
    ]);

    $data = $request->all();

    // Verifica se há uma imagem enviada
    if ($request->hasFile('cover_image')) {
        $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
    } else {
        $data['cover_image'] = 'covers/default_cover.jpg'; // Imagem padrão
    }

    // Criar o livro no banco de dados
    Book::create($data);

    return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
}

    // Editar
    public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    // Update
    public function update(Request $request, Book $book)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'publisher_id' => 'required|exists:publishers,id',
        'author_id' => 'required|exists:authors,id',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação da imagem
    ]);

    $data = $request->all();

    // Verifica se há uma nova imagem enviada
    if ($request->hasFile('cover_image')) {
        // Apagar a imagem antiga, se existir
        if ($book->cover_image) {
            \Storage::disk('public')->delete($book->cover_image);
        }

        // Salvar a nova imagem no diretório 'covers'
        $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
    }

    // Atualiza o livro com os dados
    $book->update($data);

    return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
}
    public function show(Book $book)
    {
        // Carregando autor, editora e categoria do livro com eager loading
        $book->load(['author', 'publisher', 'category']);
        $users = User::all();

         return view('books.show', compact('book','users'));

    }
    public function index()
    {
        // Carregar os livros com autores usando eager loading e paginação
        $books = Book::with('author')->paginate(20);

        return view('books.index', compact('books'));

    }
    public function destroy(Book $book)
    {
        // Verifica se o livro tem uma imagem de capa e, se tiver, exclui o arquivo
        if ($book->cover_image) {
            \Storage::disk('public')->delete($book->cover_image);  // Exclui a imagem da pasta pública
        }

        // Exclui o livro do banco de dados
        $book->delete();

        // Redireciona de volta para a lista de livros com uma mensagem de sucesso
        return redirect()->route('books.index')->with('success', 'Livro excluído com sucesso.');
    }
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'publisher_id' => 'required|exists:publishers,id',
        'author_id' => 'required|exists:authors,id',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Se uma imagem foi enviada, salve-a, caso contrário, use uma imagem padrão
    if ($request->hasFile('cover_image')) {
        $cover_image = $request->file('cover_image')->store('covers', 'public');
    } else {
        $cover_image = 'covers/default_cover.jpg'; // Imagem padrão
    }

    // Criar o livro
    $book = new Book();
    $book->title = $request->title;
    $book->publisher_id = $request->publisher_id;
    $book->author_id = $request->author_id;
    $book->category_id = $request->category_id;
    $book->cover_image = $cover_image;
    $book->save();

    return redirect()->route('books.index')->with('success', 'Livro adicionado com sucesso!');
}
}
