<div class="mb-3">
  <label for="nombre" class="form-label">Nombre:</label>
  <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
         value="{{ old('nombre', $client->nombre ?? '') }}" required>
  @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label for="email" class="form-label">Email:</label>
  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
         value="{{ old('email', $client->email ?? '') }}">
  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label for="telefono" class="form-label">Teléfono:</label>
  <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
         value="{{ old('telefono', $client->telefono ?? '') }}">
  @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label for="monto" class="form-label">Monto $:</label>
  <input type="number" step="0.01" name="monto" class="form-control @error('monto') is-invalid @enderror"
         value="{{ old('monto', $client->monto ?? '') }}" required>
  @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
  <input type="date" name="fecha_inicio" id="fecha_inicio" 
         class="form-control @error('fecha_inicio') is-invalid @enderror"
         value="{{ old('fecha_inicio', isset($loan) ? $loan->fecha_inicio->format('Y-m-d') : '') }}" required>
  @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
