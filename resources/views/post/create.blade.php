<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}

            <a class="mb-5 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            href="{{ route('posts.index') }}">Posts</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- component -->
            <div class="heading text-center font-bold text-2xl m-5 text-gray-800">New Post</div>
            <style>
                body {
                    background: white !important;
                }
            </style>

            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <div
                    class="editor mx-auto w-10/12 flex flex-col text-gray-800 border border-gray-300 p-4 shadow-lg max-w-2xl">
                    <input class="title bg-gray-100 border border-gray-300 p-2 mb-4 outline-none" spellcheck="false"
                        placeholder="Title" type="text" name="title">
                    <textarea class="description bg-gray-100 sec p-3 h-60 border border-gray-300 outline-none" spellcheck="false"
                        placeholder="Describe everything about this post here" name="description"></textarea>

                    <!-- buttons -->
                    <div class="buttons flex items-center justify-center m-2">

                        <button
                            class="btn border border-indigo-500 p-1 px-4 font-semibold cursor-pointer text-gray-200 ml-2 bg-indigo-500"
                            type="submit">
                            Post
                        </button>
                    </div>
                </div>
            </form>


        </div>
    </div>
</x-app-layout>
