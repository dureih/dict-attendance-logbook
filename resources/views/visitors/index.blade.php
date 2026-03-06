@extends('layouts.app')
@section('title', 'Log Entry — DICT Attendance')

@section('content')
<div class="section-title">
  <div class="title-dot"></div>
  New Log Entry
</div>

<div class="card">
  <div class="card-header">
    <h2>
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      Personal Information
    </h2>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('visitors.store') }}">
      @csrf

      {{-- NAME --}}
      <div class="form-group">
        <label class="form-label">Full Name <span class="req">*</span></label>
        <div class="cols-4">
          <div>
            <input type="text" name="last_name" class="form-input @error('last_name') error @enderror"
              placeholder="Last Name" value="{{ old('last_name') }}" autocomplete="off">
            @error('last_name') <p class="error-msg">{{ $message }}</p> @enderror
            <p class="hint">Last Name</p>
          </div>
          <div>
            <input type="text" name="first_name" class="form-input @error('first_name') error @enderror"
              placeholder="First Name" value="{{ old('first_name') }}" autocomplete="off">
            @error('first_name') <p class="error-msg">{{ $message }}</p> @enderror
            <p class="hint">First Name</p>
          </div>
          <div>
            <input type="text" name="middle_initial" class="form-input" placeholder="M.I."
              maxlength="5" value="{{ old('middle_initial') }}" autocomplete="off">
            <p class="hint">M.I.</p>
          </div>
          <div>
            <select name="name_extension" class="form-select">
              <option value="">Ext.</option>
              @foreach(['Jr.','Sr.','II','III','IV','V'] as $ext)
                <option value="{{ $ext }}" {{ old('name_extension') == $ext ? 'selected' : '' }}>{{ $ext }}</option>
              @endforeach
            </select>
            <p class="hint">Extension</p>
          </div>
        </div>
      </div>

      <hr class="form-divider">

      {{-- AGE & GENDER --}}
      <div class="cols-2">
        <div class="form-group">
          <label class="form-label">Age <span class="req">*</span></label>
          <input type="number" name="age" class="form-input @error('age') error @enderror"
            placeholder="e.g. 25" min="1" max="120" value="{{ old('age') }}">
          @error('age') <p class="error-msg">{{ $message }}</p> @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Gender <span class="req">*</span></label>
          <select name="gender" class="form-select @error('gender') error @enderror">
            <option value="">Select Gender</option>
            @foreach(['Male','Female','Prefer not to say'] as $g)
              <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
          </select>
          @error('gender') <p class="error-msg">{{ $message }}</p> @enderror
        </div>
      </div>

      <hr class="form-divider">

      {{-- ADDRESS --}}
      <div class="form-group">
        <label class="form-label">Address <span class="req">*</span></label>
        <div class="cols-3">

          {{-- PROVINCE --}}
          <div>
            <label class="form-label" style="font-size:.72rem;">Province <span class="req">*</span></label>
            <select name="province" id="provinceSelect" class="form-select @error('province') error @enderror" onchange="loadMunicipalities()">
              <option value="">Select Province</option>
            </select>
            @error('province') <p class="error-msg">{{ $message }}</p> @enderror
          </div>

          {{-- MUNICIPALITY --}}
          <div>
            <label class="form-label" style="font-size:.72rem;">Municipality <span class="req">*</span></label>
            <select name="municipality" id="municipalitySelect" class="form-select @error('municipality') error @enderror" onchange="loadBarangays()" disabled>
              <option value="">Select Municipality</option>
            </select>
            @error('municipality') <p class="error-msg">{{ $message }}</p> @enderror
          </div>

          {{-- BARANGAY --}}
          <div>
            <label class="form-label" style="font-size:.72rem;">Barangay <span class="req">*</span></label>
            <select name="barangay" id="barangaySelect" class="form-select @error('barangay') error @enderror" disabled>
              <option value="">Select Barangay</option>
            </select>
            @error('barangay') <p class="error-msg">{{ $message }}</p> @enderror
          </div>

        </div>
      </div>

      <hr class="form-divider">

      {{-- CONTACT NUMBER & PURPOSE OF VISIT --}}
      <div class="cols-2">
        <div class="form-group">
          <label class="form-label">Contact Number <span class="req">*</span></label>
          <input type="tel" name="contact_number" id="contactInput"
            class="form-input @error('contact_number') error @enderror"
            placeholder="e.g. 0912 345 6789"
            maxlength="13"
            value="{{ old('contact_number') }}"
            autocomplete="off"
            oninput="formatContact(this)">
          <p class="hint" id="contactHint">Integers only · Format: 0912 345 6789 · 11 digits</p>
          @error('contact_number') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
          <label class="form-label">Purpose of Visit <span class="req">*</span></label>
          <select name="purpose" id="purposeSelect2" class="form-select @error('purpose') error @enderror" onchange="handlePurposeChange()">
            <option value="">Select Purpose</option>
            @php
              $purposes = [
                'Inquire','ICT Technical Training','Meeting',
                'Access to ICT Equipment & Internet','Use of Co-Working Space',
                'Virtual/Online Training','Government Services',
                'Use of Training Room','Use of Conference Area','Others',
              ];
            @endphp
            @foreach($purposes as $purpose)
              <option value="{{ $purpose }}" {{ old('purpose') == $purpose ? 'selected' : '' }}>{{ $purpose }}</option>
            @endforeach
          </select>
          @error('purpose') <p class="error-msg">{{ $message }}</p> @enderror
          <div id="othersBox" style="display:none; margin-top:10px;">
            <input type="text" name="purpose_other" id="purposeOther"
              class="form-input @error('purpose_other') error @enderror"
              placeholder="Please specify..."
              value="{{ old('purpose_other') }}" autocomplete="off">
            @error('purpose_other') <p class="error-msg">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

      {{-- SUBMIT BUTTON CENTERED --}}
      <div style="display:flex; justify-content:center; margin-top:24px;">
        <button type="submit" class="btn btn-primary" style="font-size:1rem;padding:13px 60px;">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
          SUBMIT
        </button>
      </div>

    </form>
  </div>
</div>

<script>
// ── ADDRESS DATA ───────────────────────────────────────────────
const ADDRESS = {
  "Antique": {
    "Anini-y": ["Aranguel","Bayo Grande","Bayo Pequeño","Butuan","Casay","Casay Viejo","Ipil","Jandayan Grande","Jandayan Pequeño","Mag-aba","Osorio","Poblacion","San Bernardo","Santander","Seration","Tigbolo"],
    "Barbaza": ["Aras-asan","Atabay","Banbanan","Barangbang","Bunga","Esperanza","Flores","Jayubo","Lanas","Lawa-an","Madrangka","Manlucahoc","Maquiraya","P. Ilustre","Poblacion","San Agustin","San Antonio","San Jose","Talisay","Tigrayan"],
    "Belison": ["Bugo","Cadiao","Caray","Fulo","Maibo","Poblacion","San Rafael","Sumaray","Tigbawan"],
    "Bugasong": ["Alegria","Alon","Amburayan","Badiongan","Barasalon","Dalipe","Iba","Imba","Lapaz","Libas","Lisub","Maantol","Maasin","Mag-aba","Paliwan","Pangalao","Pangalo","Panlagangan","Pantao","Pasong","Pis-anan","Poblacion","Quiajo","Quinapondan","Quinsapondan"],
    "Caluya": ["Alegria","Bonbon","Cagbuhangin","Dawis","Dionela","Harigue","Hininga-an","Imba","Lookan","Mahahacnon","Manipis","Nauhang","Poblacion","San Isidro","Sibolo","Tinogboc"],
    "Culasi": ["Alojipan","Bacalan","Baladjay","Batbatan Island","Baye","Buang","Bugo","Cabil-ogan","Calibnog","Culasi Proper","Igcalawagan","Igcapuyas","Igpalge","Igtulingan","Jamabalud","Lacaron","Laoang","Libot","Lindero","Maasin","Magsaysay","Naba","Nadsadan","Nagustan","Naisud","Nalbang","Napuid","Nasuli","Pandan","Pantao","Pasong","Pis-anan","Poblacion"],
    "Hamtic": ["Abiera","Aras-asan","Araw-araw","Bacong","Bagumbayan","Balabago","Bonga","Bugo","Bugtong Lumangan","Bugtong Naulid","Camancijan","Canipayan","Casit-an","Dalipe","Dao","Gua-an","Guintas","Igbical","Igbita","Ingwan","Lantangan","Lenabeng","Liburon","Linaban","Mabini","Mangin","Masanag","Mayabig","Pajo","Poblacion","Salvacion","San Juan","Sandayong","Santa Ana","Santo Rosario","Supa","Tibiao","Tigbolo","Tigmamale"],
    "Laua-an": ["Abiera","Agsirab","Alojipan","Barasalon","Bua Norte","Bua Sur","Cabugao","Dalipe","Daorong","Gabutong","Igbalogo","Igbangcal","Igcadag","Igcado","Igcalawagan","Igcapuyas","Igcasicad","Igdalaquit","Igdanlog","Igpanolong","Igpawa","Igpayong","Igpuringon","Igsuming","Igtulingan","Jaguimit","Laua-an Poblacion","Lubang","Magsaysay","Maibo","Malabor","Malapad","Malinao","Manaol","Napuid","Nasuli","Naulid","Pis-anan","Poblacion Norte","Poblacion Sur","Quiajo","Quinoguingan","Quinsapondan","Salngan","Tene","Tigbalang"],
    "Libertad": ["Baybay","Buang","Bugo","Igcalawagan","Igcapuyas","Igpalge","Inabasan","Inaladan","Ingwan","Libertad Proper","Linabuan Norte","Linabuan Sur","Magsaysay","Maibo","Malangas","Pantao","Pasong","Pis-anan","Poblacion","San Roque","Sinalang"],
    "Pandan": ["Aracay","Badiangan","Bagumbayan","Batuan","Bayo","Cabugao","Cabugan","Callan","Capoyuan","Casit-an","Cubay","Guintas","Idiacacan","Igbical","Igburi","Igpalge","Igpanolong","Ilano","Imborong","Lanag","Laua-an","Liong","Mag-aba","Malapad na Palay","Naba","Nadsadan","Nagustan","Naisud","Nalbang","Napuid","Nasuli","Pandan Proper","Paningayan","Pis-anan","Poblacion","Quiajo","San Jose","Santa Ana","Santo Rosario","Tigbaboy"],
    "Patnongon": ["Aparicio","Aras-asan","Araw-araw","Bita-og","Buang","Bugo","Callan","Camangahan","Casit-an","Cubay","Dalipe","Igbalogo","Igbangcal","Igcado","Igcalawagan","Igcapuyas","Igcasicad","Igdalaquit","Igdanlog","Igpanolong","Igpawa","Igpayong","Igpuringon","Igsuming","Igtulingan","Jaguimit","Laua-an","Lubang","Magsaysay","Patnongon Proper","Pis-anan","Poblacion"],
    "San Jose": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)","Barangay 6 (Pob.)","Barangay 7 (Pob.)","Barangay 8 (Pob.)","Bulanao","Camancijan","Cosio","Cubay Norte","Cubay Sur","Ipil","Jibao-an","Lantangan","Lisub A","Lisub B","Maibo","Maingpit","Malapad na Palay","Manginman","Marigne","Maybato Norte","Maybato Sur","Naksaan","Nazuni","Ondol","Patria","Pungsod","San Angel","San Fernando","San Isidro","San Nicolas","San Ramon","San Roque","Santa Ana","Santo Rosario Norte","Santo Rosario Sur","Tigbalang","Tigbawan","Timbangan"],
    "San Remigio": ["Aglinab","Bagumbayan","Balisbisan","Bancal","Bari","Barusbus","Bugtong","Camangahan","Capoyuan","Casit-an","Catmon","Duyong","Igbical","Igbita","Igburi","Igcadag","Igcado","Inabasan","Jimamanay","Lawis","Libertad","Mabini","Magsaysay","Maibo","Malangas","Mangin","Masanag","Mayabig","Naba","Pajo","Pasong","Pis-anan","Poblacion","San Roque","Sinalang","Tene"],
    "Sebaste": ["Abiera","Aracay","Barasalon","Batuan","Buang","Bugo","Bugtong Lumangan","Igcalawagan","Igcapuyas","Igpalge","Igtulingan","Inabasan","Inaladan","Ingwan","Laua-an","Libertad","Magsaysay","Maibo","Malapad","Malinao","Manaol","Napuid","Nasuli","Naulid","Pis-anan","Poblacion","Sebaste Proper","Sinalang"],
    "Sibalom": ["Alegria","Alon","Amburayan","Atipolo","Badiongan","Barangbang","Bunga","Busuayan","Cagbalogo","Camangahan","Capoyuan","Casit-an","Cubay","Dalipe","Dao","Esperanza","Flores","Igbalogo","Igbangcal","Igcadag","Igcado","Igcalawagan","Igcapuyas","Igcasicad","Igdalaquit","Igdanlog","Igpanolong","Igpawa","Igpayong","Igpuringon","Igsuming","Igtulingan","Jaguimit","Jayubo","Jimamanay","Lawis","Lawa-an","Libo-on","Libertad","Linabuan Norte","Linabuan Sur","Lisub","Maasin","Madrangka","Magsaysay","Maibo","Malapad","Malinao","Manaol","Manlucahoc","Maquiraya","Napuid","Nasuli","Naulid","P. Ilustre","Paliwan","Palo","Pangalao","Pangalo","Panlagangan","Pantao","Pasong","Pis-anan","Poblacion","Quiajo","Quinapondan","Quinsapondan","Salvacion","San Agustin","San Antonio","San Jose","San Juan","Santa Ana","Sibalom Proper","Sinalang","Tagudtud","Talisay","Tene","Tibiao","Tigbalang","Tigbolo","Tigmamale"],
    "Tibiao": ["Alegria","Alon","Amburayan","Badiongan","Barasalon","Buang","Bugo","Bugtong","Camangahan","Capoyuan","Casit-an","Cubay","Dalipe","Igbalogo","Igbangcal","Igcadag","Igcado","Igcalawagan","Igcapuyas","Igcasicad","Igdalaquit","Igdanlog","Igpanolong","Igpawa","Igpayong","Igpuringon","Igsuming","Igtulingan","Jaguimit","Jayubo","Laua-an","Libo-on","Lisub","Maasin","Magsaysay","Maibo","Malapad","Malinao","Manaol","Napuid","Nasuli","Naulid","Pis-anan","Poblacion","Quiajo","Quinapondan","Quinsapondan","Tibiao Proper"],
    "Tobias Fornier": ["Abiera","Agsirab","Alojipan","Barasalon","Bua Norte","Bua Sur","Cabugao","Dalipe","Daorong","Gabutong","Igbalogo","Igbangcal","Igcadag","Igcado","Igcalawagan","Igcapuyas","Igcasicad","Igdalaquit","Igdanlog","Igpanolong","Igpawa","Igpayong","Igpuringon","Igsuming","Igtulingan","Jaguimit","Laua-an","Lubang","Magsaysay","Maibo","Malabor","Malapad","Malinao","Manaol","Napuid","Nasuli","Naulid","Pis-anan","Poblacion Norte","Poblacion Sur","Quiajo","Quinoguingan","Quinsapondan","Salngan","Tene","Tobias Fornier Proper"],
    "Valderrama": ["Abiera","Agsirab","Alojipan","Barasalon","Bua Norte","Bua Sur","Cabugao","Dalipe","Daorong","Gabutong","Igbalogo","Igbangcal","Igcadag","Igcado","Igcalawagan","Igcapuyas","Igcasicad","Igdalaquit","Igdanlog","Igpanolong","Igpawa","Igpayong","Igpuringon","Igsuming","Igtulingan","Jaguimit","Laua-an","Lubang","Magsaysay","Maibo","Malabor","Malapad","Malinao","Manaol","Napuid","Nasuli","Naulid","Pis-anan","Poblacion","Quiajo","Quinoguingan","Quinsapondan","Salngan","Tene","Valderrama Proper"]
  },
  "Aklan": {
    "Kalibo": ["Andagao","Balabag","Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)","Barangay 6 (Pob.)","Barangay 7 (Pob.)","Barangay 8 (Pob.)","Barangay 9 (Pob.)","Bula","Buswang New","Buswang Old","Caano","Estancia","Linabuan Norte","Linabuan Sur","Mabilo","Nalook","Ochando","Pinonoy","Recreational","Tinigaw"],
    "Altavas": ["Agbago","Agdugayan","Bariri","Cabugao","Colongcolong","Ibajay","Linayasan","Lumaynay","Lupo","Malibu","Man-up","Odiong","Polo","Poblacion","Quirbien","San Antonio","San Roque","Singay"],
    "Balete": ["Alas-as","Biga-a","Cabug-cabug","Calamcan","Gau-an","Guinatu-an","Lambayao","Lupo","Man-up","Mina-a","Pis-anan","Poblacion"],
    "Banga": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Gao","Guinatu-an","Ibajay","Linayasan","Lumaynay","Poblacion"],
    "Batan": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Buruanga": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Ibajay": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Lezo": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Libacao": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Madalag": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Makato": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Malay": ["Boracay Island","Cabugao","Camaligan","Catmon","Cawayan","Poblacion"],
    "Malinao": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Nabas": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "New Washington": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Numancia": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"],
    "Tangalan": ["Cabugao","Camaligan","Catmon","Cawayan","Dayhagon","Poblacion"]
  },
  "Capiz": {
    "Roxas City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)","Barangay 6 (Pob.)","Barangay 7 (Pob.)","Barangay 8 (Pob.)","Barangay 9 (Pob.)","Barangay 10 (Pob.)"],
    "Cuartero": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Dao": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Dumalag": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Dumarao": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Ivisan": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Jamindan": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Mambusao": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Panay": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Tapaz": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"]
  },
  "Iloilo": {
    "Iloilo City": ["Arevalo","Bonifacio","City Proper","Jaro","La Paz","Lapuz","Mandurriao","Molo","Pavia","San Jose"],
    "Passi City": ["Agsinaya","Alibayog","Anajas","Balabag","Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)","Barangay 6 (Pob.)"],
    "Ajuy": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Alimodian": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Barotac Nuevo": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Barotac Viejo": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Cabatuan": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Calinog": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Duenas": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Dumangas": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"]
  },
  "Guimaras": {
    "Jordan": ["Alaguisoc","Balcon Melliza","Balcon Maravilla","Bugnay","Buluangan","Espinosa","Hoskyn","Lawi","Morobuan","Poblacion","Rizal","San Miguel","Sta. Teresa","Tacay","Tamborong"],
    "Buenavista": ["Agsanayan","Alaguisoc","Bagumbayan","Balcon Melliza","Bugnay","Buluangan","Espinosa","Hoskyn","Lawi","Morobuan","Poblacion","Rizal","San Miguel","Sta. Teresa"],
    "Nueva Valencia": ["Alaguisoc","Balcon Melliza","Bugnay","Buluangan","Espinosa","Hoskyn","Lawi","Morobuan","Poblacion","Rizal","San Miguel","Sta. Teresa","Tacay","Tamborong"],
    "San Lorenzo": ["Alaguisoc","Balcon Melliza","Bugnay","Buluangan","Espinosa","Hoskyn","Lawi","Morobuan","Poblacion","Rizal","San Miguel","Sta. Teresa","Tacay","Tamborong"],
    "Sibunag": ["Alaguisoc","Balcon Melliza","Bugnay","Buluangan","Espinosa","Hoskyn","Lawi","Morobuan","Poblacion","Rizal","San Miguel","Sta. Teresa","Tacay","Tamborong"]
  },
  "Negros Occidental": {
    "Bacolod City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)","Barangay 6 (Pob.)","Barangay 7 (Pob.)","Barangay 8 (Pob.)","Barangay 9 (Pob.)","Barangay 10 (Pob.)"],
    "Bago City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Cadiz City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Escalante City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Himamaylan City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Kabankalan City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "La Carlota City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Sagay City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Silay City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"],
    "Victorias City": ["Barangay 1 (Pob.)","Barangay 2 (Pob.)","Barangay 3 (Pob.)","Barangay 4 (Pob.)","Barangay 5 (Pob.)"]
  }
};

// ── LOAD PROVINCES ON PAGE LOAD ────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
  const provSel = document.getElementById('provinceSelect');
  Object.keys(ADDRESS).forEach(province => {
    const opt = document.createElement('option');
    opt.value = province;
    opt.textContent = province;
    if (province === 'Antique') opt.selected = true;
    provSel.appendChild(opt);
  });
  loadMunicipalities();
});

// ── LOAD MUNICIPALITIES ────────────────────────────────────────
function loadMunicipalities() {
  const province = document.getElementById('provinceSelect').value;
  const munSel = document.getElementById('municipalitySelect');
  const brySel = document.getElementById('barangaySelect');

  munSel.innerHTML = '<option value="">Select Municipality</option>';
  brySel.innerHTML = '<option value="">Select Barangay</option>';
  munSel.disabled = true;
  brySel.disabled = true;

  if (!province || !ADDRESS[province]) return;

  Object.keys(ADDRESS[province]).forEach(mun => {
    const opt = document.createElement('option');
    opt.value = mun;
    opt.textContent = mun;
    munSel.appendChild(opt);
  });
  munSel.disabled = false;
}

// ── LOAD BARANGAYS ─────────────────────────────────────────────
function loadBarangays() {
  const province = document.getElementById('provinceSelect').value;
  const municipality = document.getElementById('municipalitySelect').value;
  const brySel = document.getElementById('barangaySelect');

  brySel.innerHTML = '<option value="">Select Barangay</option>';
  brySel.disabled = true;

  if (!province || !municipality || !ADDRESS[province][municipality]) return;

  ADDRESS[province][municipality].forEach(brgy => {
    const opt = document.createElement('option');
    opt.value = brgy;
    opt.textContent = brgy;
    brySel.appendChild(opt);
  });
  brySel.disabled = false;
}

// ── CONTACT NUMBER ─────────────────────────────────────────────
function formatContact(input) {
  let val = input.value.replace(/\D/g, '');
  if (val.length > 11) val = val.slice(0, 11);
  if (val.length > 7) {
    val = val.slice(0, 4) + ' ' + val.slice(4, 7) + ' ' + val.slice(7);
  } else if (val.length > 4) {
    val = val.slice(0, 4) + ' ' + val.slice(4);
  }
  input.value = val;

  const digits = input.value.replace(/\D/g, '');
  const hint = document.getElementById('contactHint');
  if (digits.length === 11) {
    hint.style.color = '#059669';
    hint.textContent = '✓ Valid number';
  } else if (digits.length > 0) {
    hint.style.color = 'var(--gray-400)';
    hint.textContent = digits.length + '/11 digits';
  } else {
    hint.style.color = 'var(--gray-400)';
    hint.textContent = 'Integers only · Format: 0912 345 6789 · 11 digits';
  }
}

// ── PURPOSE ────────────────────────────────────────────────────
function handlePurposeChange() {
  const val = document.getElementById('purposeSelect2').value;
  const othersBox = document.getElementById('othersBox');
  const othersInput = document.getElementById('purposeOther');
  if (val === 'Others') {
    othersBox.style.display = 'block';
    othersInput.required = true;
  } else {
    othersBox.style.display = 'none';
    othersInput.required = false;
    othersInput.value = '';
  }
}
</script>
@endsection
