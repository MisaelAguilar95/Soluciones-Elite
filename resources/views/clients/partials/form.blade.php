<div class="mb-3">
  <label for="nombre" class="form-label">Nombre:</label>
  <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
         value="{{ old('nombre', $client->nombre ?? '') }}" required>
  @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label for="curp" class="form-label">Curp:</label>
  <input type="text" name="curp" class="form-control @error('curp') is-invalid @enderror"
         value="{{ old('curp', $client->curp ?? '') }}" maxlength="18" required>
  @error('curp') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label for="address" class="form-label">Dirección:</label>
  <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
         value="{{ old('address', $client->address ?? '') }}" required>
  @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3" hidden>
  <label for="email" class="form-label">Email:</label>
  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
         value="emai@email.com">
  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label for="telefono" class="form-label">Teléfono:</label>
  <input type="text" name="telefono" maxlength="10" class="form-control @error('telefono') is-invalid @enderror"
         value="{{ old('telefono', $client->telefono ?? '') }}">
  @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label for="monto" class="form-label">Monto $:</label>
  <input type="number" step="0.01" name="monto" class="form-control @error('monto') is-invalid @enderror"
         value="{{ old('monto', $client->monto ?? '') }}" min="1" maxlength="12" required>
  @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label for="user_id" class="form-label">Asignar Usuario:</label>
  <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
    <option value="">-- Selecciona un usuario --</option>
    @foreach($users as $user)
      <option value="{{ $user->id }}"
        {{ old('user_id', $client->user_id ?? '') == $user->id ? 'selected' : '' }}>
        {{ $user->name }}
      </option>
    @endforeach
  </select>
  @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
@php
    $fechaInicio = isset($loan) && $loan->fecha_inicio
        ? \Carbon\Carbon::parse($loan->fecha_inicio)->format('Y-m-d')
        : '';
@endphp

<div class="mb-3">
  <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
  <input type="date" name="fecha_inicio" id="fecha_inicio" 
       class="form-control @error('fecha_inicio') is-invalid @enderror"
       value="{{ old('fecha_inicio', $fechaInicio) }}" required>
  @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
 @if(isset($loan))
      <input type="hidden" name="loan_id" id="loan_id" value="{{ $loan->id }}">
  @endif
</div>

<div class="mb-3">
  <label for="folio_pagare" class="form-label">Folio Pagare:</label>
  <input type="text" name="folio_pagare" class="form-control @error('folio_pagare') is-invalid @enderror"
         value="{{ old('folio_pagare', $loan->folio_pagare ?? '') }}" maxlength="8" required>
  @error('folio_pagare') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
  <label for="aval" class="form-label">Aval:</label>
  <input type="text" name="aval" class="form-control @error('aval') is-invalid @enderror"
         value="{{ old('aval', $client->aval ?? '') }}" required>
  @error('aval') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
