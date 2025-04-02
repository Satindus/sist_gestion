<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de inicio de sesión
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }
    
    /**
     * Procesar el inicio de sesión
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $usuario = Usuario::where('nombre_usuario', $request->username)
                        ->where('activo', true)
                        ->first();
        
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Las credenciales proporcionadas son incorrectas.'
                ], 422);
            }
            
            throw ValidationException::withMessages([
                'username' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }
        
        Auth::login($usuario, $request->remember);
        
        // Actualizar último acceso
        $usuario->ultimo_acceso = now();
        $usuario->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard')
            ]);
        }
        
        return redirect()->intended(route('dashboard'));
    }
    
    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}