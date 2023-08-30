<?php

/**
 * save book cover image and returns string value filename
 * */
function uploadBookCover(\CodeIgniter\HTTP\Files\UploadedFile|null $coverImage): string|null
{
    $coverImageFileName = $coverImage->getRandomName();
    // save cover image file
    $save = $coverImage->move(BOOK_COVER_PATH, $coverImageFileName);

    return $save ? $coverImageFileName : null;
}

/**
 * delete former image file if it's not default image or not empty
 * */
function deleteBookCover(string|null $coverImageFileName)
{
    $filePath = BOOK_COVER_PATH . DIRECTORY_SEPARATOR . $coverImageFileName;

    if (
        !empty($coverImageFileName)
        && file_exists($filePath)
        && $coverImageFileName != DEFAULT_BOOK_COVER
        && !str_contains($coverImageFileName, 'book-')
    ) {
        return unlink($filePath);
    } else {
        return false;
    }
}

/**
 * upload new image, delete the old one, then returns new filename
 * */
function updateBookCover(\CodeIgniter\HTTP\Files\UploadedFile|null $newCoverImage, string|null $formerCoverImageFileName)
{
    $newCoverImageFileName = uploadBookCover($newCoverImage);
    deleteBookCover($formerCoverImageFileName);

    return $newCoverImageFileName;
}

function deleteMembersQRCode(string|null $filename): bool
{
    $filePath = MEMBERS_QR_CODE_PATH . $filename;

    if (!empty($filename) && file_exists($filePath)) {
        return unlink($filePath);
    } else {
        return false;
    }
}

function deleteLoansQRCode(string|null $filename): bool
{
    $filePath = LOANS_QR_CODE_PATH . $filename;

    if (!empty($filename) && file_exists($filePath)) {
        return unlink($filePath);
    } else {
        return false;
    }
}
