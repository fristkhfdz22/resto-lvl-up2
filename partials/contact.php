<section id="contact" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Kontak & Reservasi</h2>
        <form action="contact_submit.php" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Anda" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Anda" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date">Tanggal Reservasi</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="time">Waktu Reservasi</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
            </div>
            <div class="form-group">
                <label for="message">Pesan</label>
                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Pesan Anda" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Pesan & Reservasi</button>
        </form>
    </div>
</section>
