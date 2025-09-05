@extends('layouts.app')

@section('title', 'Configurar Dispositivo')

@section('content')
<div class="device-config-container">
    <div class="device-card">
        <h2>📱 Configuração de Dispositivo IoT</h2>
        <p>Configure seu dispositivo IoT para conectar à rede WiFi</p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('device.save') }}" class="device-form">
            @csrf
            
            <div class="form-group">
                <label for="ssid">Nome da Rede WiFi (SSID)</label>
                <input
                    type="text"
                    id="ssid"
                    name="ssid"
                    value="{{ old('ssid', request('ssid')) }}"
                    required
                    class="form-input"
                    placeholder="Digite o nome da rede WiFi"
                />
            </div>

            <div class="form-group">
                <label for="password">Senha da Rede WiFi</label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="form-input pr-12"
                        placeholder="Digite a senha da rede"
                    />
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white opacity-70 hover:opacity-100"
                    >
                        <span id="passwordToggle">👁️</span>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="device_name">Nome do Dispositivo</label>
                <input
                    type="text"
                    id="device_name"
                    name="device_name"
                    value="{{ old('device_name') }}"
                    required
                    class="form-input"
                    placeholder="Ex: Sensor Temperatura Sala"
                />
            </div>

            <div class="form-group">
                <label for="device_type">Tipo do Dispositivo</label>
                <select id="device_type" name="device_type" required class="form-input">
                    <option value="">Selecione o tipo</option>
                    <option value="sensor" {{ old('device_type') == 'sensor' ? 'selected' : '' }}>Sensor</option>
                    <option value="atuador" {{ old('device_type') == 'atuador' ? 'selected' : '' }}>Atuador</option>
                    <option value="monitor" {{ old('device_type') == 'monitor' ? 'selected' : '' }}>Monitor</option>
                    <option value="controlador" {{ old('device_type') == 'controlador' ? 'selected' : '' }}>Controlador</option>
                </select>
            </div>

            <div class="form-group">
                <label for="department">Departamento</label>
                <select id="department" name="department" required class="form-input">
                    <option value="">Selecione o departamento</option>
                    <option value="producao" {{ old('department') == 'producao' ? 'selected' : '' }}>Produção</option>
                    <option value="qualidade" {{ old('department') == 'qualidade' ? 'selected' : '' }}>Qualidade</option>
                    <option value="manutencao" {{ old('department') == 'manutencao' ? 'selected' : '' }}>Manutenção</option>
                    <option value="administrativo" {{ old('department') == 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="mac_address">Endereço MAC (opcional)</label>
                <input
                    type="text"
                    id="mac_address"
                    name="mac_address"
                    value="{{ old('mac_address', 'Auto-gerado') }}"
                    readonly
                    class="form-input bg-gray-200"
                    placeholder="Será gerado automaticamente"
                />
                <small class="text-gray-300 text-sm">O endereço MAC será gerado automaticamente</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-button" id="submitBtn">
                    <span id="submitText">💾 Salvar Configuração</span>
                </button>
                <button type="button" onclick="resetForm()" class="reset-button">
                    🔄 Limpar Formulário
                </button>
            </div>
        </form>
    </div>

    <!-- Resultado da API -->
    @if(session('api_result'))
        <div class="device-card">
            <h2>✅ Configuração Salva com Sucesso!</h2>
            <div class="api-result">
                <div class="result-header">
                    <h3>📡 Tópico MQTT Criado</h3>
                </div>
                <div class="topic-info">
                    <h4>Nome do Tópico:</h4>
                    <div class="topic-name">{{ session('api_result.topic') }}</div>
                    <h4>ID do Dispositivo:</h4>
                    <div class="device-id">{{ session('api_result.deviceId') }}</div>
                    <h4>Timestamp:</h4>
                    <div class="timestamp">{{ session('api_result.timestamp') }}</div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        passwordToggle.textContent = '👁️';
    }
}

function resetForm() {
    if (confirm('Tem certeza que deseja limpar o formulário?')) {
        document.querySelector('.device-form').reset();
        document.getElementById('mac_address').value = 'Auto-gerado';
    }
}

// Gerar MAC address único
function generateMacAddress() {
    const mac = 'XX:XX:XX:XX:XX:XX'.replace(/X/g, function() {
        return Math.floor(Math.random() * 16).toString(16).toUpperCase();
    });
    document.getElementById('mac_address').value = mac;
}

// Gerar MAC address ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    generateMacAddress();
});

// Validação do formulário
document.querySelector('.device-form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    submitBtn.disabled = true;
    submitText.textContent = '⏳ Salvando...';
    
    // Re-habilitar após 5 segundos (caso de erro)
    setTimeout(() => {
        submitBtn.disabled = false;
        submitText.textContent = '💾 Salvar Configuração';
    }, 5000);
});
</script>

<style>
.form-actions {
    margin-top: 2rem;
}

.api-result {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.result-header h3 {
    font-size: 1.8rem;
    color: var(--color-primary-light);
    margin-bottom: 1rem;
}

.topic-info h4 {
    font-size: 1.2rem;
    color: var(--color-primary-dark);
    margin-bottom: 0.5rem;
    margin-top: 1rem;
}

.topic-name, .device-id, .timestamp {
    font-family: 'Courier New', monospace;
    font-size: 1rem;
    color: var(--color-primary-light);
    white-space: pre-wrap;
    word-break: break-all;
    background: rgba(0, 0, 0, 0.2);
    padding: 0.5rem;
    border-radius: 4px;
    margin-bottom: 0.5rem;
}

.relative {
    position: relative;
}

.absolute {
    position: absolute;
}

.right-3 {
    right: 0.75rem;
}

.top-1\/2 {
    top: 50%;
}

.transform {
    transform: translateY(-50%);
}

.-translate-y-1\/2 {
    transform: translateY(-50%);
}

.text-white {
    color: white;
}

.opacity-70 {
    opacity: 0.7;
}

.hover\:opacity-100:hover {
    opacity: 1;
}

.pr-12 {
    padding-right: 3rem;
}

.bg-gray-200 {
    background-color: #e5e7eb;
}

.text-gray-300 {
    color: #d1d5db;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.mt-1 {
    margin-top: 0.25rem;
}

.mb-1 {
    margin-bottom: 0.25rem;
}

.mt-4 {
    margin-top: 1rem;
}

.mb-4 {
    margin-bottom: 1rem;
}

.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.rounded {
    border-radius: 0.25rem;
}

.border {
    border-width: 1px;
}

.border-red-400 {
    border-color: #f87171;
}

.bg-red-100 {
    background-color: #fee2e2;
}

.text-red-700 {
    color: #b91c1c;
}

.list-disc {
    list-style-type: disc;
}

.list-inside {
    list-style-position: inside;
}
</style>
@endsection

