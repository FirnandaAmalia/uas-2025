<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Voting Podcast</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Opsional: kustom gaya */
    .toast {
      position: fixed;
      bottom: 1rem;
      right: 1rem;
      background-color: rgba(72, 187, 120, 0.9);
      color: #fff;
      padding: 0.75rem 1.25rem;
      border-radius: 0.375rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      font-weight: 500;
      z-index: 1000;
    }
  </style>
</head>
<body class="bg-gray-100 p-6">
  <div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Daftar Podcast</h1>
    <div id="ideas-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($ideas as $idea)
      <div class="bg-white p-4 rounded shadow" data-id="{{ $idea->id }}">
        <h2 class="text-xl font-semibold">{{ $idea->title }}</h2>
        <p class="mt-2 text-gray-700">{{ $idea->description }}</p>
        <p class="mt-2 text-gray-800">
          Suara: <span class="vote-count">{{ $idea->votes_count }}</span>
        </p>

        <!-- Input nama pemilih -->
        <input
          type="text"
          name="voter_name"
          class="mt-2 p-2 border rounded w-full voter-name-input"
          placeholder="Masukkan nama Anda"
        >

        <div class="mt-4 flex justify-end">
          <button class="vote-btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Vote
          </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const token = document.querySelector('meta[name="csrf-token"]').content;

      document.querySelectorAll('[data-id]').forEach(card => {
        const id    = card.getAttribute('data-id');
        const btn   = card.querySelector('.vote-btn');
        const input = card.querySelector('.voter-name-input');

        btn.addEventListener('click', () => {
          const name = input.value.trim();
          if (!name) {
            return alert('Silakan masukkan nama Anda.');
          }

          // Disable sementara dan ubah teks tombol
          btn.disabled     = true;
          btn.textContent  = 'Mengirim…';

          fetch(`/vote/${id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
            },
            body: JSON.stringify({ voter_name: name }),
          })
          .then(res => res.json())
          .then(json => {
            if (json.success) {
              // Update jumlah suara
              const countEl = card.querySelector('.vote-count');
              countEl.textContent = parseInt(countEl.textContent) + 1;

              // Reset input dan tombol
              input.value      = '';
              btn.textContent  = 'Vote';
              btn.disabled     = false;

              // Tampilkan toast
              const toast = document.createElement('div');
              toast.className = 'toast';
              toast.innerText = 'Vote berhasil!';
              document.body.append(toast);
              setTimeout(() => toast.remove(), 2000);
            } else {
              alert(json.error || 'Gagal melakukan vote.');
              btn.textContent  = 'Vote';
              btn.disabled     = false;
            }
          })
          .catch(() => {
            alert('Terjadi kesalahan pada server.');
            btn.textContent  = 'Vote';
            btn.disabled     = false;
          });
        });
      });
    });
  </script>
</body>
</html>
