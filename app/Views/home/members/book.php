<?php if (empty($books)) : ?>
  <h5 class="card-title fw-semibold my-4 text-danger">Buku tidak ditemukan</h5>
  <p class="text-danger"><?= $msg ?? ''; ?></p>
<?php else : ?>
  <h5 class="card-title fw-semibold my-4">Hasil pencarian buku</h5>
  <table class="table table-hover table-striped">
    <thead class="table-light">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Sampul</th>
        <th scope="col">Judul</th>
        <th scope="col">Penerbit</th>
        <th scope="col">Kategori</th>
        <th scope="col">Rak</th>
        <th scope="col">Stok tersisa</th>
        <th scope="col" class="text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="table-group-divider">
      <?php $i = 1; ?>
      <?php foreach ($books as $book) : ?>
        <?php if (!$book['deleted_at']) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <div class="d-flex justify-content-center" style="max-width: 150px; height: 120px;">
                <?php
                $coverImageFilePath = BOOK_COVER_URI . $book['book_cover'];

                $coverImageUrl = base_url((!empty($book['book_cover']) && file_exists($coverImageFilePath))
                  ? $coverImageFilePath
                  : BOOK_COVER_URI . DEFAULT_BOOK_COVER);
                ?>
                <img class="mx-auto mh-100" src="<?= $coverImageUrl; ?>" alt="<?= $book['title']; ?>">
              </div>
            </td>
            <td>
              <p><b><?= "{$book['title']} ({$book['year']})"; ?></b></p>
              <p class="text-body"><?= "Author: {$book['author']}"; ?></p>
            </td>
            <td><?= $book['publisher']; ?></td>
            <td><?= $book['category']; ?></td>
            <td><?= $book['rack']; ?></td>
            <td><?= $book['stock']; ?></td>
            <td style="width: 120px;" class="text-center">
              <?php if (intval($book['stock'] ?? 0) > 0) :
                $rndm = md5(rand(0, 10000));
              ?>
                <script>
                  let book<?= $book['id'] . $rndm; ?>Element = document.getElementById('book<?= $book['slug']; ?>');

                  const book<?= $book['id'] . $rndm; ?> = {
                    slug: "<?= $book['slug']; ?>",
                    title: "<?= "{$book['title']} ({$book['year']})"; ?>",
                    cover: "<?= $book['book_cover']; ?>",
                    stock: "<?= $book['stock']; ?>"
                  };

                  function onChange<?= $book['id'] . $rndm; ?>() {
                    check(book<?= $book['id'] . $rndm; ?>Element);
                    select(book<?= $book['id'] . $rndm; ?>Element.checked, book<?= $book['id'] . $rndm; ?>);
                  }

                  book<?= $book['id'] . $rndm; ?>Element.checked = bookSelection.has('<?= $book['slug']; ?>');
                </script>
                <button type="button" class="btn btn-secondary" onclick="onChange<?= $book['id'] . $rndm; ?>()">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="book<?= $book['slug']; ?>" onchange="onChange<?= $book['id'] . $rndm; ?>()">
                    <label class="form-check-label">
                      Pilih
                    </label>
                  </div>
                </button>
              <?php else : ?>
                <button class="d-block btn btn-dark w-100 mb-2" disabled>
                  Stok habis
                </button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    </tbody>
  </table>
  <script>
    function check(element) {
      element.checked = !element.checked;
    }

    function select(checked, book) {
      if (checked) {
        selectBook(book);
      } else {
        unselectBook(book.slug);
      }
    }
  </script>
<?php endif; ?>