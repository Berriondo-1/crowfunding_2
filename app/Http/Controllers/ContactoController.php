<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactoController extends Controller
{
    public function form()
    {
        // Muestra la vista de contacto
        return view('contacto');
    }

    public function enviar(Request $request)
    {
        $data = $request->validate([
            'nombre'  => 'required|string|max:255',
            'email'   => 'required|email',
            'tipo'    => 'nullable|string|max:100',
            'mensaje' => 'required|string',
        ]);

        // De momento solo lo dejamos en el log.
        // Luego se puede cambiar por enviar correo o guardar en BD.
        Log::info('Nuevo mensaje de contacto', $data);

        return back()->with('status', '¡Gracias! Hemos recibido tu mensaje.');
    }
}
