# BooktypeThemeBuilder4mpdf
Buidling themes for Booktype, including epub, editor and PDF (using mpdf).

This application runs in the browser and allows to create themes for Booktype. www.booktype.pro

Themes basically meaning a set of css files to stype the in-browser Booktype editor, 
the EPUB / MOBI e-book file and the PDF created using the mpdf library. All at once.

The mpdf library is not provided with this repository and needs to be installed seperately. It is needed to create the PDF files.

The fonts are not provided with this repository. They are copied over from the folder `ttfonts` inside the mpdf library.

If you use different / custom fonts than listed in the file `_config/config_fonts.php` you need to change that file.
Each font needs to be `ttf` to work with the mpdf library. Each font family needs to contain four files: 
regular, italics, bold, and bold-italics. All of these are set in the file. 
IMPORTANT: mpdf does not like uppercase characters in the font-family names. Use lowercase for your font-family names, 
because the php snippet which is used later to add to the mpdf font configuration is generated from the array inside 
`_config/config_fonts.php`.