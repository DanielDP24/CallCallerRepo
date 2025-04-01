<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteInfo
{
    private $clientes;

    public function __construct($filePath)
    {
        $this->clientes = $this->cargarDatos($filePath);
    }

    private function cargarDatos($filePath)
    {
        $clientes = [];
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle); // Omitimos la cabecera
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($data) >= 3 && !empty($data[0]) && !empty($data[1]) && !empty($data[2])) {
                    $clientes[] = [
                        'empresa' => $data[0],
                        'nombre' => $data[1],
                        'email' => $data[2]
                    ];
                }
            }
            fclose($handle);
        }
        return $clientes;
    }

    private function obtenerDatoNoVacio($campo)
    {
        do {
            $cliente = $this->clientes[array_rand($this->clientes)];
        } while (empty($cliente[$campo]));
        return $cliente[$campo];
    }

    public function obtenerNombreAleatorio()
    {
        return $this->obtenerDatoNoVacio('nombre');
    }

    public function obtenerEmailAleatorio()
    {
        return $this->obtenerDatoNoVacio('email');
    }

    public function obtenerEmpresaAleatoria()
    {
        return $this->obtenerDatoNoVacio('empresa');
    }
}

// Uso del código
$clienteInfo = new ClienteInfo("ClientesIberia.csv");

echo "Nombre: " . $clienteInfo->obtenerNombreAleatorio() . "\n";
echo "Email: " . $clienteInfo->obtenerEmailAleatorio() . "\n";
echo "Empresa: " . $clienteInfo->obtenerEmpresaAleatoria() . "\n";
