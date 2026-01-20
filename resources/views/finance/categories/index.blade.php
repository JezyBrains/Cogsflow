@extends('layouts.app')

@section('page_title', 'Finance Categories')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-[10px] font-bold text-zenith-300 uppercase tracking-widest mb-2 gap-2 items-center">
                    <a href="{{ route('finance.index') }}" class="hover:text-zenith-500">Finance</a>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-zenith-500">Categories</span>
                </nav>
                <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight">Finance Categories</h2>
            </div>
            <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')"
                class="bg-zenith-500 hover:bg-zenith-600 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-zenith-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Category
            </button>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Income Categories -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-zenith-400 uppercase tracking-[0.2em] px-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Income Categories
                </h3>
                <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <tbody class="divide-y divide-zenith-50">
                            @foreach($categories->where('type', 'income') as $category)
                                <tr class="hover:bg-zenith-50/30 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="font-bold text-zenith-800">{{ $category->name }}</span>
                                        <p class="text-[10px] text-zenith-400 font-medium mt-1">
                                            {{ $category->description ?: 'No description' }}
                                        </p>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick='editCategory(@json($category))'
                                                class="p-2 text-zenith-300 hover:text-zenith-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('finance.categories.destroy', $category->id) }}"
                                                method="POST"
                                                onsubmit="zenithConfirmAction(event, 'Dismantle Category', 'Authorize deletion of this financial category? This operation is irreversible.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-zenith-300 hover:text-red-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expense Categories -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-zenith-400 uppercase tracking-[0.2em] px-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Expense Categories
                </h3>
                <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <tbody class="divide-y divide-zenith-50">
                            @foreach($categories->where('type', 'expense') as $category)
                                <tr class="hover:bg-zenith-50/30 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="font-bold text-zenith-800">{{ $category->name }}</span>
                                        <p class="text-[10px] text-zenith-400 font-medium mt-1">
                                            {{ $category->description ?: 'No description' }}
                                        </p>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick='editCategory(@json($category))'
                                                class="p-2 text-zenith-300 hover:text-zenith-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <form action="{{ route('finance.categories.destroy', $category->id) }}"
                                                method="POST"
                                                onsubmit="zenithConfirmAction(event, 'Dismantle Category', 'Authorize deletion of this financial category? This operation is irreversible.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-zenith-300 hover:text-red-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addCategoryModal"
        class="hidden fixed inset-0 bg-zenith-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="px-10 pt-10 pb-6 flex items-center justify-between border-b border-zenith-50">
                <h3 class="text-2xl font-display font-black text-zenith-900 tracking-tight">Add New Category</h3>
                <button onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
                    class="text-zenith-300 hover:text-zenith-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('finance.categories.store') }}" method="POST" class="p-10 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">Name</label>
                    <input type="text" name="name" required
                        class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all"
                        placeholder="Category Name">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">Type</label>
                    <select name="type" required
                        class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all"
                        placeholder="Optional description..."></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-zenith-900 hover:bg-black text-white py-5 rounded-2xl font-black text-[12px] uppercase tracking-[0.2em] shadow-xl transition-all">
                    Create Category
                </button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editCategoryModal"
        class="hidden fixed inset-0 bg-zenith-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="px-10 pt-10 pb-6 flex items-center justify-between border-b border-zenith-50">
                <h3 class="text-2xl font-display font-black text-zenith-900 tracking-tight">Edit Category</h3>
                <button onclick="document.getElementById('editCategoryModal').classList.add('hidden')"
                    class="text-zenith-300 hover:text-zenith-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-10 space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">Name</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">Type</label>
                    <select name="type" id="edit_type" required
                        class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3"
                        class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-zenith-500 hover:bg-zenith-600 text-white py-5 rounded-2xl font-black text-[12px] uppercase tracking-[0.2em] shadow-xl transition-all">
                    Update Category
                </button>
            </form>
        </div>
    </div>

    <script>
        function editCategory(category) {
            document.getElementById('editForm').action = "/finance/categories/" + category.id;
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_type').value = category.type;
            document.getElementById('edit_description').value = category.description || '';
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }
    </script>
@endsection