<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function config(Request $request)
    {
        $ssid = $request->get('ssid', '');
        return view('device.config', compact('ssid'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'ssid' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'device_name' => 'required|string|max:255',
            'device_type' => 'required|string|in:sensor,atuador,monitor,controlador',
            'department' => 'required|string|in:producao,qualidade,manutencao,administrativo',
        ]);

        try {
            // Gerar MAC address único
            $macAddress = $this->generateMacAddress();
            
            // Dados do dispositivo
            $deviceData = [
                'name' => $request->device_name,
                'macAddress' => $macAddress,
                'deviceType' => $request->device_type,
                'department' => $request->department,
                'ssid' => $request->ssid,
                'password' => $request->password,
            ];

            // Enviar para API MQTT
            $response = Http::post('http://localhost:8000/api/mqtt/topics', [
                'name' => $this->generateTopicName($deviceData),
                'description' => "Dispositivo {$request->device_name} - {$request->device_type} ({$macAddress}) - Criado em " . now()->toISOString(),
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                return redirect()->back()->with('api_result', [
                    'success' => true,
                    'topic' => $result['data']['name'] ?? 'N/A',
                    'deviceId' => $result['data']['id'] ?? 'N/A',
                    'timestamp' => now()->format('d/m/Y H:i:s'),
                ]);
            } else {
                throw new \Exception('Erro ao criar tópico MQTT: ' . $response->body());
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao salvar configuração: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function generateMacAddress()
    {
        return strtoupper(implode(':', array_map(function() {
            return sprintf('%02x', mt_rand(0, 255));
        }, range(0, 5))));
    }

    private function generateTopicName($deviceData)
    {
        $timestamp = time();
        $sanitizedDepartment = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $deviceData['department']));
        $sanitizedMac = str_replace(':', '', $deviceData['macAddress']);
        
        return "device/{$sanitizedDepartment}/{$sanitizedMac}_{$timestamp}";
    }
}

