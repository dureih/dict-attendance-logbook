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
              maxlength="1" value="{{ old('middle_initial') }}" autocomplete="off"
              oninput="this.value=this.value.replace(/[^a-zA-Z]/g,'').toUpperCase()">
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
            <div class="combo-wrap">
              <input type="text" id="provinceInput"
                class="form-input @error('province') error @enderror"
                placeholder="Type province..." autocomplete="off"
                oninput="filterProvince()" onfocus="openProvince()" onblur="hideDropdown('provinceDropdown', 200)">
              <input type="hidden" name="province" id="provinceValue">
              <ul class="combo-dropdown" id="provinceDropdown"></ul>
            </div>
            @error('province') <p class="error-msg">{{ $message }}</p> @enderror
          </div>

          {{-- MUNICIPALITY --}}
          <div>
            <label class="form-label" style="font-size:.72rem;">Municipality <span class="req">*</span></label>
            <div class="combo-wrap">
              <input type="text" id="municipalityInput"
                class="form-input @error('municipality') error @enderror"
                placeholder="Select province first..." autocomplete="off"
                oninput="filterMunicipality()" onfocus="openMunicipality()" onblur="hideDropdown('municipalityDropdown', 200)" disabled>
              <input type="hidden" name="municipality" id="municipalityValue">
              <ul class="combo-dropdown" id="municipalityDropdown"></ul>
            </div>
            @error('municipality') <p class="error-msg">{{ $message }}</p> @enderror
          </div>

          {{-- BARANGAY --}}
          <div>
            <label class="form-label" style="font-size:.72rem;">Barangay <span class="req">*</span></label>
            <div class="combo-wrap">
              <input type="text" id="barangayInput"
                class="form-input @error('barangay') error @enderror"
                placeholder="Select municipality first..." autocomplete="off"
                oninput="filterBarangay()" onfocus="openBarangay()" onblur="hideDropdown('barangayDropdown', 200)" disabled>
              <input type="hidden" name="barangay" id="barangayValue">
              <ul class="combo-dropdown" id="barangayDropdown"></ul>
            </div>
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

<style>
/* ── COMBO BOX ─────────────────────────────────────────────── */
.combo-wrap {
  position: relative;
}
.combo-dropdown {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #fff;
  border: 1.5px solid var(--blue);
  border-top: none;
  border-radius: 0 0 8px 8px;
  max-height: 210px;
  overflow-y: auto;
  z-index: 999;
  list-style: none;
  margin: 0;
  padding: 4px 0;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}
.combo-dropdown::-webkit-scrollbar { width: 5px; }
.combo-dropdown::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
.combo-item {
  padding: 9px 14px;
  font-size: .84rem;
  cursor: pointer;
  color: #374151;
  transition: background .12s;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.combo-item:hover {
  background: #EFF6FF;
  color: var(--blue);
  font-weight: 600;
}
.combo-empty {
  padding: 10px 14px;
  font-size: .82rem;
  color: #9ca3af;
  font-style: italic;
  list-style: none;
}
.combo-item mark {
  background: #dbeafe;
  color: var(--blue);
  font-weight: 700;
  border-radius: 2px;
  padding: 0 1px;
}
</style>

<script>
// ── ADDRESS DATA ───────────────────────────────────────────────
const ADDRESS = {
  "Antique": {
    "Anini-y": ["Bayo Grande","Bayo Pequeño","Butuan","Casay","Casay Viejo","Iba","Igbarabatuan","Igpalge","Igtumarom","Lisub A","Lisub B","Mabuyong","Magdalena","Nasuli C","Nato","Poblacion","Sagua","Salvacion","San Francisco","San Ramon","San Roque","Tagaytay","Talisayan"],
    "Barbaza": ["Baghari","Bahuyan","Beri","Biga-a","Binangbang","Binangbang Centro","Binanu-an","Cadiao","Calapadan","Capoyuan","Cubay","Embrangga-an","Esparar","Gua","Idao","Igpalge","Igtunarum","Integasan","Ipil","Jinalinan","Lanas","Langcaon","Lisub","Lombuyan","Mablad","Magtulis","Marigne","Mayabay","Mayos","Nalusdan","Narirong","Palma","Poblacion","San Antonio","San Ramon","Soligao","Tabongtabong","Tig-alaran","Yapo"],
    "Belison": ["Borocboroc","Buenavista","Concepcion","Delima","Ipil","Maradiona","Mojon","Poblacion","Rombang","Salvacion","Sinaja"],
    "Bugasong": ["Anilawan","Arangote","Bagtason","Camangahan","Centro Ilawod","Centro Ilaya","Centro Pojo","Cubay North","Cubay South","Guija","Igbalangao","Igsoro","Ilaures","Jinalinan","Lacayon","Maray","Paliwan","Pangalcagan","Sabang East","Sabang West","Tagudtud North","Tagudtud South","Talisay","Tica","Tono-an","Yapu","Zaragoza"],
    "Caluya": ["Alegria","Bacong","Banago","Bonbon","Dawis","Dionela","Harigue","Hininga-an","Imba","Masanag","Poblacion","Sabang","Salamento","Semirara","Sibato","Sibay","Sibolo","Tinogboc"],
    "Culasi": ["Alojipan","Bagacay","Balac-balac","Batbatan Island","Batonan Norte","Batonan Sur","Bita","Bitadton Norte","Bitadton Sur","Buenavista","Buhi","Camancijan","Caridad","Carit-an","Centro Norte","Centro Poblacion","Centro Sur","Condes","Esperanza","Fe","Flores","Jalandoni","Janlagasi","Lamputong","Lipata","Magsaysay","Malacañang","Malalison Island","Maniguin","Naba","Osorio","Paningayan","Salde","San Antonio","San Gregorio","San Juan","San Luis","San Pascual","San Vicente","Simbola","Tigbobolo","Tinabusan","Tomao","Valderama"],
    "Hamtic": ["Apdo","Asluman","Banawon","Bia-an","Bongbongan I-II","Bongbongan III","Botbot","Budbudan","Buhang","Calacja I","Calacja II","Calala","Cantulan","Caridad","Caromangay","Casalngan","Dangcalan","Del Pilar","Fabrica","Funda","General Fullon","Gov. Evelio B. Javier","Guintas","Igbical","Igbucagay","Inabasan","Ingwan-Batangan","La Paz","Linaban","Malandog","Mapatag","Masanag","Nalihawan","Pamandayan","Pasu-Jungao","Piape I","Piape II","Piape III","Pili 1 2 3","Poblacion 1","Poblacion 2","Poblacion 3","Poblacion 4","Poblacion 5","Pu-ao","Suloc","Villavert-Jimenez"],
    "Laua-an": ["Bagongbayan","Banban","Bongbongan","Cabariwan","Cadajug","Canituan","Capnayan","Casit-an","Guiamon","Guinbanga-an","Guisijan","Igtadiao","Intao","Jaguikican","Jinalinan","Lactudan","Latazon","Laua-an","Liberato","Lindero","Liya-liya","Loon","Lugta","Lupa-an","Magyapo","Maria","Mauno","Maybunga","Necesito","Oloc","Omlot","Pandanan","Paningayan","Pascuala","Poblacion","San Ramon","Santiago","Tibacan","Tigunhao","Virginia"],
    "Libertad": ["Barusbus","Bulanao","Centro Este","Centro Weste","Codiong","Cubay","Igcagay","Inyawan","Lindero","Maramig","Pajo","Panangkilon","Paz","Pucio","San Roque","Taboc","Tinigbas","Tinindugan","Union"],
    "Pandan": ["Aracay","Badiangan","Bagumbayan","Baybay","Botbot","Buang","Cabugao","Candari","Carmen","Centro Norte","Centro Sur","Dionela","Dumrog","Duyong","Fragante","Guia","Idiacacan","Jinalinan","Luhod-Bayang","Maadios","Mag-aba","Napuid","Nauring","Patria","Perfecta","San Andres","San Joaquin","Santa Ana","Santa Cruz","Santa Fe","Santo Rosario","Talisay","Tingib","Zaldivar"],
    "Patnongon": ["Alvañiz","Amparo","Apgahan","Aureliana","Badiangan","Bernaldo A. Julagting","Carit-an","Cuyapiao","Gella","Igbarawan","Igbobon","Igburi","La Rioja","Mabasa","Macarina","Magarang","Magsaysay","Padang","Pandanan","Patlabawon","Poblacion","Quezon","Salaguiawan","Samalague","San Rafael","Tamayoc","Tigbalogo","Tobias Fornier","Villa Crespo","Villa Cruz","Villa Elio","Villa Flores","Villa Laua-an","Villa Sal","Villa Salomon","Vista Alegre"],
    "San Jose de Buenavista": ["Atabay","Badiang","Barangay 1","Barangay 2","Barangay 3","Barangay 4","Barangay 5","Barangay 6","Barangay 7","Barangay 8","Bariri","Bugarot","Cansadan","Durog","Funda-Dalipe","Igbonglo","Inabasan","Madrangca","Magcalon","Malaiba","Maybato Norte","Maybato Sur","Mojon","Pantao","San Angel","San Fernando","San Pedro","Supa"],
    "San Remigio": ["Agricula","Alegria","Aningalan","Atabay","Bagumbayan","Baladjay","Banbanan","Barangbang","Bawang","Bugo","Bulan-bulan","Cabiawan","Cabunga-an","Cadolonan","Carawisan I","Carawisan II","Carmelo I","Carmelo II","General Fullon","General Luna","Iguirindon","Insubuan","La Union","Lapak","Lumpatan","Magdalena","Maragubdub","Nagbangi I","Nagbangi II","Nasuli","Orquia","Osorio I","Osorio II","Panpanan I","Panpanan II","Poblacion","Ramon Magsaysay","Rizal","San Rafael","Sinundolan","Sumaray","Trinidad","Tubudan","Vilvar","Walker"],
    "Sebaste": ["Abiera","Aguila","Alegre","Aras-asan","Bacalan","Callan","Idio","Nauhon","P. Javier","Poblacion"],
    "Sibalom": ["Alangan","Bari","Biga-a","Bongbongan I","Bongbongan II","Bongsod","Bontol","Bugnay","Bulalacao","Cabanbanan","Cabariuan","Cabladan","Cadoldolan","Calo-oy","Calog","Catmon","Catungan I","Catungan II","Catungan III","Catungan IV","Cubay-Napultan","Cubay-Sermon","District I","District II","District III","District IV","Egaña","Esperanza I","Esperanza II","Esperanza III","Igcococ","Igdagmay","Igdalaquit","Iglanot","Igpanolong","Igparas","Igsuming","Ilabas","Imparayan","Inabasan","Indag-an","Initan","Insarayan","Lacaron","Lagdo","Lambayagan","Luna","Luyang","Maasin","Mabini","Millamena","Mojon","Nagdayao","Nazareth","Odiong","Olaga","Pangpang","Panlagangan","Pantao","Pasong","Pis-anan","Rombang","Salvacion","San Juan","Sido","Solong","Tabongtabong","Tig-ohot","Tigbalua I","Tigbalua II","Tordesillas","Tulatula","Valentin Grasparil","Villafont","Villahermosa","Villar"],
    "Tibiao": ["Alegre","Amar","Bandoja","Castillo","Esparagoza","Importante","La Paz","Malabor","Martinez","Natividad","Pitac","Poblacion","Salazar","San Francisco Norte","San Francisco Sur","San Isidro","Santa Ana","Santa Justa","Santo Rosario","Tigbaboy","Tuno"],
    "Tobias Fornier": ["Abaca","Aras-asan","Arobo","Atabay","Atiotes","Bagumbayan","Balloscas","Balud","Barasanan A","Barasanan B","Barasanan C","Bariri","Camandagan","Cato-ogan","Danawan","Diclum","Fatima","Gamad","Igbalogo","Igbangcal-A","Igbangcal-B","Igbangcal-C","Igcabuad","Igcadac","Igcado","Igcalawagan","Igcapuyas","Igcasicad","Igdalaguit","Igdanlog","Igdurarog","Igtugas","Lawigan","Lindero","Manaling","Masayo","Nagsubuan","Nasuli-A","Opsan","Paciencia","Poblacion Norte","Poblacion Sur","Portillo","Quezon","Salamague","Santo Tomas","Tacbuyan","Tene","Villaflor","Ysulat"],
    "Valderrama": ["Alon","Bakiang","Binanogan","Borocboroc","Bugnay","Buluangan I","Buluangan II","Bunsod","Busog","Cananghan","Canipayan","Cansilayan","Culyat","Iglinab","Igmasandig","Lublub","Manlacbo","Pandanan","San Agustin","Takas","Tigmamale","Ubos"]
  },
  "Aklan": {
    "Altavas": ["Cabangila","Cabugao","Catmon","Dalipdip","Ginictan","Linayasan","Lumaynay","Lupo","Man-up","Odiong","Poblacion","Quinasay-an","Talon","Tibiao"],
    "Balete": ["Aranas","Arcangel","Calizo","Cortes","Feliciano","Fulgencio","Guanko","Morales","Oquendo","Poblacion"],
    "Banga": ["Agbanawan","Bacan","Badiangan","Cerrudo","Cupang","Daguitan","Daja Norte","Daja Sur","Dingle","Jumarap","Lapnag","Libas","Linabuan Sur","Mambog","Mangan","Muguing","Pagsanghan","Palale","Poblacion","Polo","Polocate","San Isidro","Sibalew","Sigcay","Taba-ao","Tabayon","Tinapuay","Torralba","Ugsod","Venturanza"],
    "Batan": ["Ambolong","Angas","Bay-ang","Cabugao","Caiyang","Camaligan","Camanci","Ipil","Lalab","Lupit","Magpag-ong","Magubahay","Mambuquiao","Man-up","Mandong","Napti","Palay","Poblacion","Songcolan","Tabon"],
    "Buruanga": ["Alegria","Bagongbayan","Balusbos","Bel-is","Cabugan","El Progreso","Habana","Katipunan","Mayapay","Nazareth","Panilongan","Poblacion","Santander","Tag-osip","Tigum"],
    "Ibajay": ["Agbago","Agdugayan","Antipolo","Aparicio","Aquino","Aslum","Bagacay","Batuan","Buenavista","Bugtongbato","Cabugao","Capilijan","Colongcolong","Laguinbanua","Mabusao","Malindog","Maloco","Mina-a","Monlaque","Naile","Naisud","Naligusan","Ondoy","Poblacion","Polo","Regador","Rivera","Rizal","San Isidro","San Jose","Santa Cruz","Tagbaya","Tul-ang","Unat","Yawan"],
    "Kalibo": ["Andagaw","Bachaw Norte","Bachaw Sur","Briones","Buswang New","Buswang Old","Caano","Estancia","Linabuan Norte","Mabilo","Mobo","Nalook","Poblacion","Pook","Tigayon","Tinigaw"],
    "Lezo": ["Agcawilan","Bagto","Bugasongan","Carugdog","Cogon","Ibao","Mina","Poblacion","Santa Cruz","Santa Cruz Bigaa","Silakat-Nonok","Tayhawan"],
    "Libacao": ["Agmailig","Alfonso XII","Batobato","Bonza","Calacabian","Calamcan","Can-awan","Casit-an","Dalagsa-an","Guadalupe","Janlud","Julita","Luctoga","Magugba","Manika","Ogsip","Ortega","Oyang","Pampango","Pinonoy","Poblacion","Rivera","Rosal","Sibalew"],
    "Madalag": ["Alaminos","Alas-as","Bacyang","Balactasan","Cabangahan","Cabilawan","Catabana","Dit-ana","Galicia","Guinatu-an","Logohon","Mamba","Maria Cristina","Medina","Mercedes","Napnot","Pang-itan","Paningayan","Panipiason","Poblacion","San Jose","Singay","Talangban","Talimagao","Tigbawan"],
    "Makato": ["Agbalogo","Aglucay","Alibagon","Bagong Barrio","Baybay","Cabatanga","Cajilo","Calangcang","Calimbajan","Castillo","Cayangwan","Dumga","Libang","Mantiguib","Poblacion","Tibiawan","Tina","Tugas"],
    "Malay": ["Argao","Balabag","Balusbus","Cabulihan","Caticlan","Cogon","Cubay Norte","Cubay Sur","Dumlog","Manoc-Manoc","Motag","Naasug","Nabaoy","Napaan","Poblacion","San Viray","Yapak"],
    "Malinao": ["Banaybanay","Biga-a","Bulabud","Cabayugan","Capataga","Cogon","Dangcalan","Kinalangay Nuevo","Kinalangay Viejo","Lilo-an","Malandayon","Manhanip","Navitas","Osman","Poblacion","Rosario","San Dimas","San Ramon","San Roque","Sipac","Sugnod","Tambuan","Tigpalas"],
    "Nabas": ["Alimbo-Baybay","Buenafortuna","Buenasuerte","Buenavista","Gibon","Habana","Laserna","Libertad","Magallanes","Matabana","Nagustan","Pawa","Pinatuad","Poblacion","Rizal","Solido","Tagororoc","Toledo","Unidos","Union"],
    "New Washington": ["Candelaria","Cawayan","Dumaguit","Fatima","Guinbaliwan","Jalas","Jugas","Lawa-an","Mabilo","Mataphao","Ochando","Pinamuk-an","Poblacion","Polo","Puis","Tambak"],
    "Numancia": ["Albasan","Aliputos","Badio","Bubog","Bulwang","Camanci Norte","Camanci Sur","Dongon East","Dongon West","Joyao-joyao","Laguinbanua East","Laguinbanua West","Marianos","Navitas","Poblacion","Pusiw","Tabangka"],
    "Tangalan": ["Afga","Baybay","Dapdap","Dumatad","Jawili","Lanipga","Napatag","Panayakan","Poblacion","Pudiot","Tagas","Tamalagon","Tamokoe","Tondog","Vivo"]
  },
  "Capiz": {
    "Cuartero": ["Agcabugao","Agdahon","Agnaga","Angub","Balingasag","Bito-on Ilawod","Bito-on Ilaya","Bun-od","Carataya","Lunayan","Mahabang Sapa","Mahunodhunod","Maindang","Mainit","Malagab-i","Nagba","Poblacion Ilawod","Poblacion Ilaya","Poblacion Takas","Puti-an","San Antonio","Sinabsaban"],
    "Dao": ["Aganan","Agtambi","Agtanguay","Balucuan","Bita","Centro","Daplas","Duyoc","Ilas Sur","Lacaron","Malonoy","Manhoy","Mapulang Bato","Matagnop","Nasunogan","Poblacion Ilawod","Poblacion Ilaya","Quinabcaban","Quinayuya","San Agustin"],
    "Dumalag": ["Concepcion","Consolacion","Dolores","Duran","Poblacion","San Agustin","San Jose","San Martin","San Miguel","San Rafael","San Roque","Santa Carmen","Santa Cruz","Santa Monica","Santa Rita","Santa Teresa","Santo Angel","Santo Niño","Santo Rosario"],
    "Dumarao": ["Agbatuan","Aglalana","Aglanot","Agsirab","Alipasiawan","Astorga","Bayog","Bungsuan","Calapawan","Codingle","Cubi","Dacuton","Dangula","Gibato","Guinotos","Jambad","Janguslob","Lawaan","Malonoy","Nagsulang","Ongol Ilawod","Ongol Ilaya","Poblacion Ilawod","Poblacion Ilaya","Sagrada Familia","Salcedo","San Juan","Sibariwan","Tamulalod","Taslan","Tina","Tinaytayan","Traciano"],
    "Ivisan": ["Agmalobo","Agustin Navarra","Balaring","Basiao","Cabugao","Cudian","Ilaya-Ivisan","Malocloc Norte","Malocloc Sur","Matnog","Mianay","Ondoy","Poblacion Norte","Poblacion Sur","Santa Cruz"],
    "Jamindan": ["Agambulong","Agbun-od","Agcagay","Aglibacao","Agloloway","Bayebaye","Caridad","Esperanza","Fe","Ganzon","Guintas","Igang","Jaena Norte","Jaena Sur","Jagnaya","Lapaz","Linambasan","Lucero","Maantol","Masgrau","Milan","Molet","Pangabat","Pangabuan","Pasol-o","Poblacion","San Jose","San Juan","San Vicente","Santo Rosario"],
    "Maayon": ["Aglimocon","Alasaging","Alayunan","Balighot","Batabat","Bongbongan","Cabungahan","Canapian","Carataya","Duluan","East Villaflores","Fernandez","Guinbi-alan","Indayagan","Jebaca","Maalan","Manayupit","New Guia","Old Guia","Palaguian","Parallan","Piña","Poblacion Ilawod","Poblacion Ilaya","Poblacion Tabuc","Quevedo","Quinabonglan","Quinat-uyan","Salgan","Tapulang","Tuburan","West Villaflores"],
    "Mambusao": ["Atiplo","Balat-an","Balit","Batiano","Bating","Bato Bato","Baye","Bergante","Bula","Bunga","Bungsi","Burias","Caidquid","Cala-agus","Libo-o","Manibad","Maralag","Najus-an","Pangpang Norte","Pangpang Sur","Pinay","Poblacion Proper","Poblacion Tabuc","Sinondojan","Tugas","Tumalalud"],
    "Panay": ["Agbalo","Agbanban","Agojo","Anhawon","Bagacay","Bago Chiquito","Bago Grande","Bahit","Bantique","Bato","Binangig","Binantuan","Bonga","Buntod","Butacal","Cabugao Este","Cabugao Oeste","Calapawan","Calitan","Candual","Cogon","Daga","Ilamnay","Jamul-awon","Lanipga","Lat-asan","Libon","Linao","Linateran","Lomboy","Lus-onan","Magubilan","Navitas","Pawa","Pili","Poblacion Ilawod","Poblacion Ilaya","Poblacion Tabuc","Talasa","Tanza Norte","Tanza Sur","Tico"],
    "Panitan": ["Agbabadiang","Agkilo","Agloway","Ambilay","Bahit","Balatucan","Banga-an","Cabangahan","Cabugao","Cadio","Cala-an","Capagao","Cogon","Conciencia","Ensenagan","Intampilan","Pasugue","Poblacion Ilawod","Poblacion Ilaya","Quios","Salocon","Tabuc Norte","Tabuc Sur","Timpas","Tincupon","Tinigban"],
    "Pilar": ["Balogo","Binaobawan","Blasco","Casanayan","Cayus","Dayhagan","Dulangan","Monteflor","Natividad","Olalo","Poblacion","Rosario","San Antonio","San Blas","San Esteban","San Fernando","San Nicolas","San Pedro","San Ramon","San Silvestre","Santa Fe","Sinamongan","Tabun-acan","Yating"],
    "Pontevedra": ["Agbanog","Agdalipe","Ameligan","Bailan","Banate","Bantigue","Binuntucan","Cabugao","Gabuc","Guba","Hipona","Ilawod","Ilaya","Intungcan","Jolongajog","Lantangan","Linampongan","Malag-it","Manapao","Rizal","San Pedro","Solo","Sublangon","Tabuc","Tacas","Yatingan"],
    "President Roxas": ["Aranguel","Badiangon","Bayuyan","Cabugcabug","Carmencita","Cubay","Culilang","Goce","Hanglid","Ibaca","Madulano","Manoling","Marita","Pandan","Pantalan Cabugcabug","Pinamihagan","Poblacion","Pondol","Quiajo","Sangkal","Santo Niño","Vizcaya"],
    "Roxas City": ["Adlawan","Bago","Balijuagan","Banica","Barra","Bato","Baybay","Bolo","Cabugao","Cagay","Cogon","Culajao","Culasi","Dayao","Dinginan","Dumolog","Gabu-an","Inzo Arnaldo Village","Jumaguicjic","Lanot","Lawa-an","Libas","Liong","Loctugan","Lonoy","Milibili","Mongpong","Olotayan","Poblacion I","Poblacion II","Poblacion III","Poblacion IV","Poblacion IX","Poblacion V","Poblacion VI","Poblacion VII","Poblacion VIII","Poblacion X","Poblacion XI","Punta Cogon","Punta Tabuc","San Jose","Sibaguan","Talon","Tanque","Tanza","Tiza"],
    "Sapian": ["Agsilab","Agtatacay Norte","Agtatacay Sur","Bilao","Damayan","Dapdapan","Lonoy","Majanlud","Maninang","Poblacion"],
    "Sigma": ["Acbo","Amaga","Balucuan","Bangonbangon","Capuyhan","Cogon","Dayhagon","Guintas","Malapad Cogon","Mangoso","Mansacul","Matangcong","Matinabus","Mianay","Oyong","Pagbunitan","Parian","Pinamalatican","Poblacion Norte","Poblacion Sur","Tawog"],
    "Tapaz": ["Abangay","Acuña","Agcococ","Aglinab","Aglupacan","Agpalali","Apero","Artuz","Bag-ong Barrio","Bato-bato","Buri","Camburanan","Candelaria","Carida","Cristina","Da-an Banwa","Da-an Norte","Da-an Sur","Garcia","Gebio-an","Hilwan","Initan","Katipunan","Lagdungan","Lahug","Libertad","Mabini","Maliao","Malitbog","Minan","Nayawan","Poblacion","Rizal Norte","Rizal Sur","Roosevelt","Roxas","Salong","San Antonio","San Francisco","San Jose","San Julian","San Miguel Ilawod","San Miguel Ilaya","San Nicolas","San Pedro","San Roque","San Vicente","Santa Ana","Santa Petronila","Senonod","Siya","Switch","Tabon","Tacayan","Taft","Taganghin","Taslan","Wright"]
  },
  "Guimaras": {
    "Buenavista": ["Agsanayan","Avila","Bacjao","Banban","Cansilayan","Dagsa-an","Daragan","East Valencia","Getulio","Mabini","Magsaysay","Mclain","Montpiller","Navalas","Nazaret","New Poblacion","Old Poblacion","Piña","Rizal","Salvacion","San Fernando","San Isidro","San Miguel","San Nicolas","San Pedro","San Roque","Santo Rosario","Sawang","Supang","Tacay","Taminla","Tanag","Tastasan","Tinadtaran","Umilig","Zaldivar"],
    "Jordan": ["Alaguisoc","Balcon Maravilla","Balcon Melliza","Bugnay","Buluangan","Espinosa","Hoskyn","Lawi","Morobuan","Poblacion","Rizal","San Miguel","Santa Teresa","Sinapsapan"],
    "Nueva Valencia": ["Cabalagnan","Calaya","Canhawan","Concordia Sur","Dolores","Guiwanon","Igang","Igdarapdap","La Paz","Lanipe","Lucmayan","Magamay","Napandong","Oracon Sur","Pandaraonan","Panobolon","Poblacion","Salvacion","San Antonio","San Roque","Santo Domingo","Tando"],
    "San Lorenzo": ["Aguilar","Cabano","Cabungahan","Constancia","Gaban","Igcawayan","M. Chavez","San Enrique","Sapal","Sebario","Suclaran","Tamborong"],
    "Sibunag": ["Alegria","Ayangan","Bubog","Concordia Norte","Dasal","Inampologan","Maabay","Millan","Oracon Norte","Ravina","Sabang","San Isidro","Sebaste","Tanglad"]
  },
  "Iloilo": {
    "Ajuy": ["Adcadarao","Agbobolo","Badiangan","Barrido","Bato Biasong","Bay-ang","Bucana Bunglas","Central","Culasi","Lanjagan","Luca","Malayu-an","Mangorocoro","Nasidman","Pantalan Nabaye","Pantalan Navarro","Pedada","Pili","Pinantan Diel","Pinantan Elizalde","Pinay Espinosa","Poblacion","Progreso","Puente Bunglas","Punta Buri","Rojas","San Antonio","Santo Rosario","Silagon","Tagubanhan","Taguhangin","Tanduyan","Tipacla","Tubogan"],
    "Leganes": ["Bigke","Buntatala","Cagamutan Norte","Cagamutan Sur","Calaboa","Camangay","Cari Mayor","Cari Minor","Gua-an","Guihaman","Guinobatan","Guintas","Lapayon","M. V. Hechanova","Nabitasan","Napnud","Poblacion","San Vicente"],
    "Passi City": ["Agdahon","Agdayao","Aglalana","Agtabo","Agtambo","Alimono","Arac","Ayuyan","Bacuranan","Bagacay","Batu","Bayan","Bitaogan","Buenavista","Buyo","Cabunga","Cadilang","Cairohan","Dalicanan","Gegachac","Gemat-y","Gemumua-agahon","Gines Viejo","Imbang Grande","Jaguimitan","Libo-o","Maasin","Magdungao","Malag-it Grande","Malag-it Pequeño","Mambiranan Grande","Mambiranan Pequeño","Man-it","Mantulang","Mulapula","Nueva Union","Pagaypay","Pangi","Poblacion Ilawod","Poblacion Ilaya","Punong","Quinagaringan Grande","Quinagaringan Pequeño","Sablogon","Salngan","Santo Tomas","Sarapan","Tagubong","Talongonan","Tubod","Tuburan"],
    "Pavia": ["Aganan","Amparo","Anilao","Balabag","Cabugao Norte","Cabugao Sur","Jibao-an","Mali-ao","Pagsanga-an","Pal-agon","Pandac","Purok I","Purok II","Purok III","Purok IV","Tigum","Ungka I","Ungka II"],
    "San Miguel": ["Barangay 1 Poblacion","Barangay 2 Poblacion","Barangay 3 Poblacion","Barangay 4 Poblacion","Barangay 5 Poblacion","Barangay 6 Poblacion","Barangay 7 Poblacion","Barangay 8 Poblacion","Barangay 9 Poblacion","Barangay 10","Barangay 11 Poblacion","Barangay 12 Poblacion","Barangay 13 Poblacion","Barangay 14 Poblacion","Barangay 15 Poblacion","Barangay 16 Poblacion","Consolacion","Igtambo","San Antonio","San Jose","Santa Cruz","Santa Teresa","Santo Angel","Santo Niño"],
    "Tubungan": ["Adgao","Ago","Ambarihon","Ayubo","Bacan","Badiang","Bagunanay","Balicua","Bantayanan","Batga","Bato","Bikil","Boloc","Bondoc","Borong","Buenavista","Cadabdab","Daga-ay","Desposorio","Igdampog Norte","Igdampog Sur","Igpaho","Igtuble","Ingay","Isauan","Jolason","Jona","La-ag","Lanag Norte","Lanag Sur","Male","Mayang","Molina","Morcillas","Nagba","Navillan","Pinamacalan","San Jose","Sibucauan","Singon","Tabat","Tagpu-an","Talento","Teniente Benito","Victoria","Zone I","Zone II","Zone III"],
    "Zarraga": ["Balud I","Balud II","Balud Lilo-an","Dawis Centro","Dawis Norte","Dawis Sur","Gines","Ilawod Poblacion","Ilaya Poblacion","Inagdangan Centro","Inagdangan Norte","Inagdangan Sur","Jalaud Norte","Jalaud Sur","Libongcogon","Malunang","Pajo","Sambag","Sigangao","Talauguis","Talibong","Tubigan","Tuburan","Tuburan Sulbod"]
  },
  "Negros Occidental": {
    "Bago City": ["Abuanan","Alianza","Atipuluan","Bacong-Montilla","Bagroy","Balingasag","Binubuhan","Busay","Calumangan","Caridad","Dulao","Ilijan","Jorge L. Araneta","Lag-asan","Ma-ao Barrio","Mailum","Malingin","Napoles","Pacol","Poblacion","Sagasa","Sampinit","Tabunan","Taloc"],
    "Binalbagan": ["Amontay","Bagroy","Bi-ao","Canmoros","Enclaro","Marina","Paglaum","Payao","Progreso","San Jose","San Juan","San Pedro","San Teodoro","San Vicente","Santo Rosario","Santol"],
    "Cadiz City": ["Andres Bonifacio","Banquerohan","Barangay 1 Poblacion","Barangay 2 Poblacion","Barangay 3 Poblacion","Barangay 4 Poblacion","Barangay 5 Poblacion","Barangay 6 Poblacion","Burgos","Cabahug","Cadiz Viejo","Caduha-an","Celestino Villacin","Daga","Jerusalem","Luna","Mabini","Magsaysay","Sicaba","Tiglawigan","Tinampa-an","V. F. Gustilo"],
    "Kabankalan City": ["Bantayan","Barangay 1","Barangay 2","Barangay 3","Barangay 4","Barangay 5","Barangay 6","Barangay 7","Barangay 8","Barangay 9","Binicuil","Camansi","Camingawan","Camugao","Carol-an","Daan Banua","Hilamonan","Inapoy","Linao","Locotan","Magballo","Oringao","Orong","Pinaguinpinan","Salong","Tabugon","Tagoc","Tagukon","Talubangi","Tampalon","Tan-awan","Tapi"],
    "La Carlota City": ["Ara-al","Ayungon","Balabag","Barangay I","Barangay II","Barangay III","Batuan","Cubay","Haguimit","La Granja","Nagasi","Roberto S. Benedicto","San Miguel","Yubo"],
    "Sagay City": ["Andres Bonifacio","Bato","Baviera","Bulanon","Campo Himoga-an","Campo Santiago","Colonia Divina","Fabrica","General Luna","Himoga-an Baybay","Lopez Jaena","Makiling","Malubon","Molocaboc","Old Sagay","Paraiso","Plaridel","Poblacion I","Poblacion II","Puey","Rafaela Barrera","Rizal","Taba-ao","Tadlong","Vito"],
    "Silay City": ["Bagtic","Balaring","Barangay I","Barangay II","Barangay III","Barangay IV","Barangay V","Barangay VI Poblacion","Eustaquio Lopez","Guimbala-on","Guinhalaran","Kapitan Ramon","Lantad","Mambulac","Patag","Rizal"],
    "Victorias City": ["Barangay I","Barangay II","Barangay III","Barangay IV","Barangay IX","Barangay V","Barangay VI","Barangay VI-A","Barangay VII","Barangay VIII","Barangay X","Barangay XI","Barangay XII","Barangay XIII","Barangay XIV","Barangay XIX","Barangay XIX-A","Barangay XV","Barangay XV-A","Barangay XVI","Barangay XVI-A","Barangay XVII","Barangay XVIII","Barangay XVIII-A","Barangay XX","Barangay XXI"]
  }
};

// ── COMBO BOX HELPERS ──────────────────────────────────────────
function hideDropdown(id, delay) {
  setTimeout(() => { document.getElementById(id).style.display = 'none'; }, delay);
}

function escHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function renderList(ulId, items, onSelect, query) {
  const ul = document.getElementById(ulId);
  ul.innerHTML = '';
  if (items.length === 0) {
    ul.innerHTML = '<li class="combo-empty">No results found</li>';
  } else {
    items.forEach(item => {
      const li = document.createElement('li');
      li.className = 'combo-item';
      if (query && query.length > 0) {
        const idx = item.toLowerCase().indexOf(query.toLowerCase());
        if (idx >= 0) {
          li.innerHTML =
            escHtml(item.slice(0, idx)) +
            '<mark>' + escHtml(item.slice(idx, idx + query.length)) + '</mark>' +
            escHtml(item.slice(idx + query.length));
        } else {
          li.textContent = item;
        }
      } else {
        li.textContent = item;
      }
      li.onmousedown = () => onSelect(item);
      ul.appendChild(li);
    });
  }
  ul.style.display = 'block';
}

// ── PROVINCE ──────────────────────────────────────────────────
function openProvince() {
  filterProvinceWithQuery(document.getElementById('provinceInput').value);
}
function filterProvince() {
  document.getElementById('provinceValue').value = '';
  filterProvinceWithQuery(document.getElementById('provinceInput').value);
}
function filterProvinceWithQuery(query) {
  const matches = Object.keys(ADDRESS).filter(p => p.toLowerCase().includes(query.toLowerCase()));
  renderList('provinceDropdown', matches, selectProvince, query);
}
function selectProvince(province) {
  document.getElementById('provinceInput').value = province;
  document.getElementById('provinceValue').value = province;
  document.getElementById('provinceDropdown').style.display = 'none';
  const munInput = document.getElementById('municipalityInput');
  munInput.value = ''; munInput.placeholder = 'Type municipality...'; munInput.disabled = false;
  document.getElementById('municipalityValue').value = '';
  const bryInput = document.getElementById('barangayInput');
  bryInput.value = ''; bryInput.placeholder = 'Select municipality first...'; bryInput.disabled = true;
  document.getElementById('barangayValue').value = '';
}

// ── MUNICIPALITY ──────────────────────────────────────────────
function openMunicipality() {
  filterMunicipalityWithQuery(document.getElementById('municipalityInput').value);
}
function filterMunicipality() {
  document.getElementById('municipalityValue').value = '';
  filterMunicipalityWithQuery(document.getElementById('municipalityInput').value);
}
function filterMunicipalityWithQuery(query) {
  const province = document.getElementById('provinceValue').value;
  if (!province || !ADDRESS[province]) return;
  const matches = Object.keys(ADDRESS[province]).filter(m => m.toLowerCase().includes(query.toLowerCase()));
  renderList('municipalityDropdown', matches, selectMunicipality, query);
}
function selectMunicipality(municipality) {
  document.getElementById('municipalityInput').value = municipality;
  document.getElementById('municipalityValue').value = municipality;
  document.getElementById('municipalityDropdown').style.display = 'none';
  const bryInput = document.getElementById('barangayInput');
  bryInput.value = ''; bryInput.placeholder = 'Type barangay...'; bryInput.disabled = false;
  document.getElementById('barangayValue').value = '';
}

// ── BARANGAY ──────────────────────────────────────────────────
function openBarangay() {
  filterBarangayWithQuery(document.getElementById('barangayInput').value);
}
function filterBarangay() {
  document.getElementById('barangayValue').value = '';
  filterBarangayWithQuery(document.getElementById('barangayInput').value);
}
function filterBarangayWithQuery(query) {
  const province = document.getElementById('provinceValue').value;
  const municipality = document.getElementById('municipalityValue').value;
  if (!province || !municipality || !ADDRESS[province][municipality]) return;
  const matches = ADDRESS[province][municipality].filter(b => b.toLowerCase().includes(query.toLowerCase()));
  renderList('barangayDropdown', matches, selectBarangay, query);
}
function selectBarangay(barangay) {
  document.getElementById('barangayInput').value = barangay;
  document.getElementById('barangayValue').value = barangay;
  document.getElementById('barangayDropdown').style.display = 'none';
}

// ── INIT ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
  selectProvince('Antique');
  selectMunicipality('San Jose de Buenavista');
});

// ── CONTACT NUMBER ─────────────────────────────────────────────
function formatContact(input) {
  let val = input.value.replace(/\D/g, '');
  if (val.length > 11) val = val.slice(0, 11);
  if (val.length > 7) val = val.slice(0,4)+' '+val.slice(4,7)+' '+val.slice(7);
  else if (val.length > 4) val = val.slice(0,4)+' '+val.slice(4);
  input.value = val;
  const digits = input.value.replace(/\D/g,'');
  const hint = document.getElementById('contactHint');
  if (digits.length === 11) { hint.style.color='#059669'; hint.textContent='✓ Valid number'; }
  else if (digits.length > 0) { hint.style.color='var(--gray-400)'; hint.textContent=digits.length+'/11 digits'; }
  else { hint.style.color='var(--gray-400)'; hint.textContent='Integers only · Format: 0912 345 6789 · 11 digits'; }
}

// ── PURPOSE ────────────────────────────────────────────────────
function handlePurposeChange() {
  const val = document.getElementById('purposeSelect2').value;
  const othersBox = document.getElementById('othersBox');
  const othersInput = document.getElementById('purposeOther');
  if (val === 'Others') { othersBox.style.display='block'; othersInput.required=true; }
  else { othersBox.style.display='none'; othersInput.required=false; othersInput.value=''; }
}
</script>
@endsection
