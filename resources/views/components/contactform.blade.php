@if (session()->has('status'))
    <div class="bg-green-50 border-l-4 border-green-400 p-2 my-2">
        <p class="text-sm text-green-700">
            {{ session('status')['text'] }}
        </p>
    </div>
@endif

<form action="{{ route('contactform.store') }}" method="POST" class="">
    @csrf

    <div class="py-3">
        <x-forms.input name="name" label="Name" type="text" />
    </div>
    <div class="py-3">
        <x-forms.input name="mail" label="E-Mail" type="email" />
    </div>
    <div class="py-3">
        <x-forms.textarea name="message" label="Nachricht" />
    </div>
    <div class="py-3">
        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Abschicken
        </button>
    </div>
</form>
