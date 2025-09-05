@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="home-container">
    <div class="header">
        <h1>🔌 IoT Configuration</h1>
        <p>Configure seus dispositivos IoT de forma simples e rápida</p>
    </div>

    <div class="text-center mb-8">
        <button onclick="scanNetworks()" class="scan-button" id="scanBtn">
            <span id="scanText">📡 Escanear Redes WiFi</span>
        </button>
    </div>

    <div class="networks-section" id="networksSection" style="display: none;">
        <h2>📶 Redes WiFi Disponíveis</h2>
        <div id="networksList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Networks will be loaded here -->
        </div>
    </div>

    <div id="noNetworks" class="text-center mt-8" style="display: none;">
        <div class="bg-white bg-opacity-10 rounded-lg p-6 backdrop-filter backdrop-blur-lg">
            <h3 class="text-xl font-semibold mb-2">Nenhuma rede encontrada</h3>
            <p class="opacity-90">Tente novamente ou verifique se o WiFi está ativado</p>
            <button onclick="scanNetworks()" class="mt-4 px-6 py-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                Tentar Novamente
            </button>
        </div>
    </div>
</div>

<script>
let isScanning = false;

async function scanNetworks() {
    if (isScanning) return;
    
    isScanning = true;
    const scanBtn = document.getElementById('scanBtn');
    const scanText = document.getElementById('scanText');
    
    scanBtn.disabled = true;
    scanText.textContent = '⏳ Escaneando...';
    
    try {
        // Simular escaneamento de redes (em produção, isso seria uma chamada real)
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // Simular redes encontradas
        const mockNetworks = [
            { ssid: 'WiFi-Casa', rssi: -45, security: 'WPA2' },
            { ssid: 'WiFi-Vizinho', rssi: -65, security: 'WPA2' },
            { ssid: 'IoT-Device', rssi: -30, security: 'Open' },
            { ssid: 'Office-WiFi', rssi: -55, security: 'WPA3' },
        ];
        
        displayNetworks(mockNetworks);
        
    } catch (error) {
        console.error('Erro ao escanear redes:', error);
        showNoNetworks();
    } finally {
        isScanning = false;
        scanBtn.disabled = false;
        scanText.textContent = '📡 Escanear Redes WiFi';
    }
}

function displayNetworks(networks) {
    const networksList = document.getElementById('networksList');
    const networksSection = document.getElementById('networksSection');
    const noNetworks = document.getElementById('noNetworks');
    
    if (networks.length === 0) {
        showNoNetworks();
        return;
    }
    
    networksList.innerHTML = networks.map(network => `
        <div class="network-item" onclick="selectNetwork('${network.ssid}')">
            <div class="network-info">
                <h3>${network.ssid}</h3>
                <p>📶 ${getSignalStrength(network.rssi)}</p>
                <p>🔒 ${network.security}</p>
                <div class="mt-4 text-right">
                    <button onclick="configureDevice('${network.ssid}')" class="configure-btn">
                        Configurar
                    </button>
                </div>
            </div>
        </div>
    `).join('');
    
    networksSection.style.display = 'block';
    noNetworks.style.display = 'none';
}

function showNoNetworks() {
    const networksSection = document.getElementById('networksSection');
    const noNetworks = document.getElementById('noNetworks');
    
    networksSection.style.display = 'none';
    noNetworks.style.display = 'block';
}

function getSignalStrength(rssi) {
    if (rssi >= -50) return 'Excelente';
    if (rssi >= -60) return 'Muito Boa';
    if (rssi >= -70) return 'Boa';
    if (rssi >= -80) return 'Regular';
    return 'Fraca';
}

function selectNetwork(ssid) {
    console.log('Rede selecionada:', ssid);
    configureDevice(ssid);
}

function configureDevice(ssid) {
    // Redirecionar para página de configuração
    window.location.href = `{{ route('device.config') }}?ssid=${encodeURIComponent(ssid)}`;
}

// Auto-scan ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    scanNetworks();
});
</script>

<style>
.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

@media (min-width: 768px) {
    .md\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (min-width: 1024px) {
    .lg\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

.gap-4 {
    gap: 1rem;
}

.text-center {
    text-align: center;
}

.mb-8 {
    margin-bottom: 2rem;
}

.mt-8 {
    margin-top: 2rem;
}

.mt-4 {
    margin-top: 1rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.text-xl {
    font-size: 1.25rem;
    line-height: 1.75rem;
}

.font-semibold {
    font-weight: 600;
}

.opacity-90 {
    opacity: 0.9;
}

.bg-white {
    background-color: white;
}

.bg-opacity-10 {
    background-color: rgba(255, 255, 255, 0.1);
}

.bg-opacity-20 {
    background-color: rgba(255, 255, 255, 0.2);
}

.hover\:bg-opacity-30:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

.rounded-lg {
    border-radius: 0.5rem;
}

.p-6 {
    padding: 1.5rem;
}

.px-6 {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.backdrop-filter {
    backdrop-filter: blur(10px);
}
</style>
@endsection

