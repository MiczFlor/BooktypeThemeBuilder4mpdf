<?php
error_reporting(0);

/*
* a few variables for this script
*/
$debug = "false"; // (true|false)
/*
* The source html file which is piped into mpdf
*/
$html4mpdf = "mpdf-body.html"; // (mpdf-body.html|mpdf-body_themesample.html)
/*
* When creating a PDF, should also a copy with specs of the theme be created? 
* (like: Sample_H190mm_W125mm_Font-freeserif_Size-11pt.pdf)
*/
$createcopypdf = "false"; // (true|false)

// reading available fonts
include("_config/config_fonts.php");

/*
* Array needed for mpdf PHP renderer
*/
$options = array(
  "home" => realpath(dirname(__FILE__)), // path to the folder where the sample files are
  "mpdf_files" => realpath(dirname(__FILE__))."/mpdf_files", // path to the folder where the sample files are
  "mpdf_output" => realpath(dirname(__FILE__))."/mpdf_output", // path where to create PDF
  //"mpdf_lib" => "/var/www/mpdf/", // path to mpdf library
  "mpdf_lib" => "/var/www/mpdf60-old/", // path to mpdf library
  "dirthemes" => realpath(dirname(__FILE__))."/themes", // path to the folder theme dirs are
  "output" => "static_mpdf_test.pdf", // file name of the generated file
  /*
  * kindlegen is needed to generate kindle e-book files from EPUB
  * Learn more here: http://www.amazon.com/gp/feature.html?docId=1000765211
  */
  "kindlegen" => "/home/micz/kindlegen/kindlegen", // file name of the generated file
);
/*
* the online editor needs different values from the print output
* Todo: figure out if it makes sense to change the editor size according to
* the theme. It might make the work a bit awkward. After all, the editor is not
* WYSIWYG but creates a "likelihood" of the actual theme.
* Right now I stick to the same, comfortable size. Unless the Theme uses a 
* really large font.
*/
$FontSizeEditorCSS = array(
  "9pt" => "12pt",
  "9.5pt" => "12pt",
  "10pt" => "13pt",
  "10.5pt" => "13pt",
  "11pt" => "13pt",
  "12pt" => "13pt",
  "13pt" => "13pt",
  "15pt" => "15pt",
);
/*
* some select options for the form
*/
$formoptionselectbasefontsize = "select__". implode("_", array_keys($FontSizeEditorCSS));
$formoptionselectpadding = "select__0-top:0-bottom_0-top:1-bottom_0-top:2-bottom_0-top:3-bottom_0-top:4-bottom_0-top:5-bottom_0.33-top:0.66-bottom_0.5-top:0.5-bottom_0.66-top:0.33-bottom_1-top:0-bottom_1-top:1-bottom_1-top:2-bottom_1-top:3-bottom_1-top:4-bottom_1-top:5-bottom_1.33-top:0.66-bottom_1.5-top:0.5-bottom_1.66-top:0.33-bottom_2-top:0-bottom_2-top:1-bottom_2-top:2-bottom_2-top:3-bottom_2-top:4-bottom_2-top:5-bottom_2.5-top:0.5-bottom_2.5-top:1.5-bottom_3-top:0-bottom_3-top:1-bottom_3-top:2-bottom_3-top:3-bottom_3-top:4-bottom_3-top:5-bottom_3.5-top:0.5-bottom_3.5-top:1.5-bottom_3.5-top:2.5-bottom_4-top:0-bottom_4-top:1-bottom_4-top:2-bottom_4-top:3-bottom_4-top:4-bottom_4-top:5-bottom_5-top:0-bottom_5-top:1-bottom_5-top:3-bottom_5-top:4-bottom_5-top:5-bottom_6-top:0-bottom_6-top:1-bottom_6-top:3-bottom_6-top:6-bottom_7-top:0-bottom_7-top:1-bottom_7-top:3-bottom_7-top:4-bottom_7-top:7-bottom";
$formoptionselectfontsizeem = "select__0.5_0.7_0.9_1_1.1_1.2_1.3_1.33_1.4_1.5_1.66_1.8_2_2.33_2.5_2.66_3_3.33_3.5_3.66_4_4.33_4.5_4.66_5_5.5_6_6.5_7";
$formoptionselectcaptionfontsizeem = "select__0.33_0.5_0.6_0.7_0.8_0.9_1_1.1_1.2_1.3_1.4_1.5_1.66_1.8_2_2.5_3_3.5_4_4.5_5_5.5_6_6.5_7";
$formoptionselectlineheightinteger = "select__1_2_3_4_5_6_7";
$formoptions = array(
  "page" => array(
    "prefix" => "Page",
    "elements" => array(
      "Definition" => "select__PageDefinition",
    )
  ),
  "body" => array(
    "prefix" => "Body",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontSize" => $formoptionselectbasefontsize,
      "LineHeight" => "select__0.9_1_1.1_1.2_1.3_1.4_1.5_1.6_1.7_1.8_1.9_2",
      "TextAlign" => "select__left_right_center_justify",
    )
  ),
  "pbody" => array(
    "prefix" => "BodyParagraph",
    "elements" => array(
      "Indent" => "select__none_1_2_3",
      "Spacing" => "select__none_one-line",
      //"Dropcaps" => "select__false_true",
    )
  ),
  "preformatted" => array(
    "prefix" => "Preformatted",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "Padding" => $formoptionselectpadding,
    )
  ),
  "quote" => array(
    "prefix" => "Quote",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "FontStyle" => "select__normal_italic",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "LineLeft" => "select__none_thin_light_medium_thick",
      "LineLeftColor" => "select__black_dark-grey_light-grey_white",
      "LineStyle" => "select__solid_dotted_dashed_double",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
      "LineHeight" => $formoptionselectlineheightinteger,
      "PaddingMargin2Line" => "select__0_1_2_3_4_5_6_7",
      "PaddingLine2Quote" => "select__0_1_2_3_4_5_6_7",
      "Padding" => $formoptionselectpadding,
    )
  ),
  "headertext" => array(
    "prefix" => "Header",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "Display" => "select__show_hide",
      //"Content" => "select__booktitle_pagenumber",
      "Align" => "select__outside_inside_center",
      "FontSize" => "select__tiny_small_normal",
      "Line" => "select__false_true__Line between header and text",
    )
  ),
  "footertext" => array(
    "prefix" => "Footer",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "Display" => "select__show_hide",
      //"Content" => "select__pagenumber_booktitle",
      "Align" => "select__center_inside_outside",
      "FontSize" => "select__tiny_small_normal",
      "Line" => "select__false_true__Line between footer and text"
    )
  ),
  "h1section" => array(
    "prefix" => "Part",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "BorderBottom" => "select__none_thin_medium_thick",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
    )
  ),
  "frontmatterhalftitle" => array(
    "description" => "Starting the halftitle in the frontmatter",
    "prefix" => "FrontmatterHalftitleTitle",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
    )
  ),
  "frontmatterauthor" => array(
    "prefix" => "FrontmatterHalftitleAuthor",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "Align" => "select__left_center_right",
      "FontSize" => $formoptionselectfontsizeem,
    )
  ),
  "frontmattertitle" => array(
    "description" => "Starting the actual titlepage in the frontmatter",
    "prefix" => "FrontmatterTitlepageTitle",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
    )
  ),
  "frontmattersubtitle" => array(
    "prefix" => "FrontmatterTitlepageSubtitle",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "Align" => "select__left_center_right",
      "FontSize" => $formoptionselectfontsizeem,
    )
  ),
  "frontmatterauthortitle" => array(
    "prefix" => "FrontmatterTitlepageAuthor",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "Align" => "select__left_center_right",
      "FontSize" => $formoptionselectfontsizeem,
    )
  ),
  "frontmatterbottompublisher" => array(
    "prefix" => "FrontmatterTitlepagePublisher",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "Align" => "select__left_center_right",
    )
  ),
  "frontmatterline" => array(
    "prefix" => "FrontmatterTitlepage",
    "elements" => array(
      "Line" => "select__true_false__Line between title and author"
    )
  ),
  "mpdftoc" => array(
    "prefix" => "TableOfContents",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "TopLevelFontWeight" => "select__bold_normal",
    )
  ),
  "chapterh1" => array(
    "prefix" => "ChapterHeader1",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "BorderBottom" => "select__none_thin_medium_thick",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
      "LineHeight" => $formoptionselectlineheightinteger,
      "Padding" => $formoptionselectpadding,
    )
  ),
  /*
  "chapterh1beneath" => array(
    "prefix" => "ChapterHeader1Beneath",
    "elements" => array(
      "BorderBottom" => "select__none_thin_medium_thick",
      "Align" => "select__left_center_right",
      "Width" => "select__100%_60%_50%_30%_10%",
    )
  ),
  /**/
  "bodyh1" => array(
    "prefix" => "BodyHeader1",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "BorderBottom" => "select__none_thin_medium_thick",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
      "LineHeight" => $formoptionselectlineheightinteger,
      "Padding" => $formoptionselectpadding,
    )
  ),
  "bodyh2" => array(
    "prefix" => "BodyHeader2",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "BorderBottom" => "select__none_thin_medium_thick",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
      "LineHeight" => $formoptionselectlineheightinteger,
      "Padding" => $formoptionselectpadding,
    )
  ),
  "bodyh3" => array(
    "prefix" => "BodyHeader3",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "BorderBottom" => "select__none_thin_medium_thick",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
      "LineHeight" => $formoptionselectlineheightinteger,
      "Padding" => $formoptionselectpadding,
    )
  ),
  "bodyh4" => array(
    "prefix" => "BodyHeader4",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "BorderBottom" => "select__none_thin_medium_thick",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
      "LineHeight" => $formoptionselectlineheightinteger,
      "Padding" => $formoptionselectpadding,
    )
  ),
  "bodyh5" => array(
    "prefix" => "BodyHeader5",
    "elements" => array(
      "FontFamily" => "select__FontFamily",
      "FontWeight" => "select__normal_bold",
      "Align" => "select__left_center_right",
      "TextTransform" => "select__none_uppercase",
      "BorderBottom" => "select__none_thin_medium_thick",
      "TextDecoration" => "select__none_underline_overline_line-through",
      "FontSize" => $formoptionselectfontsizeem,
      "LineHeight" => $formoptionselectlineheightinteger,
      "Padding" => $formoptionselectpadding,
    )
  ),
);

//print "<pre>"; print_r($); print "</pre>";//???
/*
* read configuration files, like the paper sizes, margins, etc.
*/
$pagepresets = json_decode(file_get_contents("_config/config_papersizes.json"), true);

/*
* Find available themes for editing
*/
$themesavail = dir_get_recursively($options['dirthemes']."/");
$tempthemes = glob($options['dirthemes']."/*", GLOB_ONLYDIR);
if(count($tempthemes) > 0) {
  $themesavail = array();
  foreach($tempthemes as $temptheme) {
    if(file_exists($temptheme."/theme_config.json")) {
      $tempthemecontent = json_decode(file_get_contents($temptheme."/theme_config.json"), true);
      $themesavail[$temptheme] = $tempthemecontent['Theme']['themenamehuman'];
    }
  }
}

/*
* Start the page with the header
*/
HTML_print_start();

/*
* See if this comes from the form post
*/
if(isset($_POST['Action'])) {
  /*
  * Yes, we got some post values.
  * Read the values from the posted form.
  */
  $ACTION = $_POST['Action'];
  $FORM = array();
  /*
  * If we have loaded a theme, then take the JSON values, else read $_POST
  */
  if($ACTION == "dosomething") {
    $FORM['Form'] = json_decode(file_get_contents($_POST['SelectTheme']."/theme_config.json"), true);
    /*
    * after we loaded the theme, also write the theme to disk, if user wants that.
    * This is useful if you want to update the themes after you made
    * changes in the CSS.
    * Loading then means: read all values and make theme for this latest version.
    */
    if($_POST['createfilesonloading'] == "true") {
      $ACTION = "createtheme";
    }
  } else {
    $FORM['Form'] = get_post_values();
  }
  /*
  * Calculate the theme values for the CSS
  */
  $FORM['Val'] = calc_css_export_values($FORM['Form']);
  // replace values for the themes
  $find = array();
  $replace   = array();
  foreach($FORM['Val'] as $key => $value) {
    //print "<br>\n$key => $value";//???
    array_push($find, "%".$key."%");
    array_push($replace, $value);
  }
  /*
  * for debugging, print the variables posted and calculated
  */
  if($debug == "true") {
    print "<pre>Form values received and calculated: \n"; print_r($FORM);print "</pre>";
    //print "<pre>"; print_r($_POST);print "</pre>";
  }
  
  /*
  * Write FORM values as JSON for later configuration
  */
  file_put_contents("mpdf_output/theme_config.json", json_encode($FORM['Form'], JSON_PRETTY_PRINT));

  /*
  * Depending on which button the user clicked, we now need to do different things.
  */
  if($ACTION == "createtheme") {
    /*
    *********************************************************************************
    * Create a theme: write all fonts and files to folder and change values
    */
    $themenamefolder = $options['dirthemes']."/".$FORM['Form']['Theme']['themenamefolder']; 

    // create theme folder
    if (!file_exists($themenamefolder)) {
    	mkdir($themenamefolder, 0777, true);
    }
    // delete content, we start from scratch, because everything could have been changed
    exec("rm -rf ".$themenamefolder."/*");
    // copy all theme raw files into folder
    exec("cp -R _assets/raw-theme/* ".$themenamefolder."/");
    // store theme json in folder
    file_put_contents($themenamefolder."/theme_config.json", json_encode($FORM['Form'], JSON_PRETTY_PRINT));  
    /*
    * copy fonts into static folder
    */
    // list all used fonts in an array
    $fontsused = array();
    foreach($FORM['Form'] as $key => $value) {
      if (strpos($key, 'FontFamily') !== false) {
        if(isset($fontfamilies[$value])) {
          $FORM['Fonts']['families'][$value] = $fontfamilies[$value];
        }
      }
    }
    /*
    * Now make a list of all font file names in the same array
    */
    foreach($FORM['Fonts']['families'] as $fontfamily => $values) {
      foreach($values as $key => $value) {
        $FORM['Fonts']['filenames'][$value] = $value;
      }
    }
    
    //print "<pre>"; print_r($FORM);print "</pre>";//???
    
    // copy the fonts to the static folder
    foreach($FORM['Fonts'] as $fontfamily => $values) {
      foreach($values as $style => $fontname) {
        exec("cp ".$options['mpdf_lib']."ttfonts/".$fontname." ".$themenamefolder."/static/fonts/");
      }
    }
    
    // write theme fonts in array format
    create_theme_theme_fonts_php();

    // write CSS to preload fonts
    create_theme_preload_css();

    // information for user UI
    create_theme_panel_html();
    
    // write info json file used by Booktype
    create_theme_info_json();
    
    // read print pdf template file
    create_theme_mpdf_css();
    
    // read screen pdf template file
    create_theme_screenpdf_css();
    
    // read browser editor CSS template file
    create_theme_editor_css();
    
    // epub.css file for EPUB creation
    create_theme_epub_css();   

    // create epub and possibly mobi file
    create_theme_ebook_files();
    
    // finally make a PDF with a sample
    create_theme_sample_pdf_file();
    
  } elseif($ACTION == "dosomething") {
    /*
    * processing all the tasks that can be chosen in the "Make it happen" form.
    * The only one that is not listed here is loading theme(s), because they need
    * to be loaded much earlier on, before we are in this place of the script.
    */
    if($_POST['SelectAction'] == "CreateFontConfigFile") {
      /*
      * We write the config file used in mpdf
      */
      $arrayfile = "<?php\n\$fontsused = array(";
      $arrayfile .= "\n// contains all fonts for all themes in one file. \n// WARNING: there are doubles if the same font is used in more than one theme.\n";
      foreach($themesavail as $themedir => $themename) {
        // exclude custom themes (all of which start their folder name with "xyz_")
        $temp = explode("/", $themedir);
        $temp = $temp[count($temp)-1];
        if(substr($temp, 0, 4) != "xyz_") {
          $arrayfile .= "\n".file_get_contents($themedir."/theme_fonts.php");
        }
      }
      $arrayfile .= "\n);\n?>";
      // write file to temp folder
      file_put_contents("themes/AA_fontconfig/theme_fonts_complete.php", $arrayfile);
      /*
      * Now make a version that can be used for MPDF font config.
      */
      // read file to get values - and overwrite doubles automatically on reading into array
      include("themes/AA_fontconfig/theme_fonts_complete.php");
      $arrayfile = ""; //this is a snippet and does not start with: "<?php\n\$fontsused = array(";
      $arrayfile .= "\n// contains all fonts used in themes once, even if used in more than one theme. \n// NOTE: this list can be used for the mpdf font config file.\n";
      foreach($fontsused as $fontfamily => $fontused) {
        $arrayfile .= "\n  \"".$fontfamily."\" => array(";
        foreach($fontused as $key => $value) {
          $arrayfile .= "\n    \"".$key."\" => \"".$value."\",";
        }
        $arrayfile .= "\n  ),";
      }
  
      // write slim version of file without doubles to folder
      file_put_contents("themes/AA_fontconfig/theme_fonts_mpdf.php", $arrayfile);
    }
    
  } else {
    /*********************************************************************
    * Create CSS and make PDF for testing when pressing "Create PDF" button
    */
    // read template file and add page measurements on top
    $stylecss = "@page {
  sheet-size: %PageWidth% %PageHeight%;  
  size: %PageWidth% %PageHeight%; 

  margin-left: %PageMarginLeft%; 
  margin-right: %PageMarginRight%;
  margin-top: %PageMarginTop%; 
  margin-bottom: %PageMarginBottom%;

  margin-footer: %PageMarginFooter%;
  odd-footer-name: html_footer-right;
  even-footer-name: html_footer-left;
  
  margin-header: %PageMarginHeader%;
  odd-header-name: html_header-right;
  even-header-name: html_header-left;         
}
";
    $stylecss .= file_get_contents('_assets/raw-theme/mpdf.css');
    $newstylecss = str_replace($find, $replace, $stylecss);
    // write template file
    file_put_contents("mpdf_files/style.css", $newstylecss);
    
    $mpdfhtml = file_get_contents('_assets/raw-html/'.$html4mpdf);
    $newmpdfhtml = str_replace($find, $replace, $mpdfhtml);
    // write template file
    file_put_contents("mpdf_files/body.html", $newmpdfhtml);
    
    /*
    * Run mPDF
    */
    include("static_booktype2mpdf.php");
    /*
    * make a copy of the output file with theme specs
    * set true of false at beginning of this file
    */
    if($createcopypdf == "true") {
      $customfilename = "Sample";
      //$customfilename .= $FORM['Form']['Theme']['themenamehuman']; // name of the theme
      $customfilename .= "_H".$FORM['Val']['PageHeight']; // page height
      $customfilename .= "_W".$FORM['Val']['PageWidth']; // page width
      $customfilename .= "_Font-".$FORM['Val']['BodyFontFamilyVal']; // font family
      $customfilename .= "_Size-".$FORM['Val']['BodyFontSizeVal']; // font size
      $customfilename .= ".pdf"; // file ending
  
      $execcustomcopy = "cp ".$file_output." ".$options["mpdf_output"]."/".$customfilename;
  
      exec($execcustomcopy);
    }
    /*
    * Create a download link and a link to go back to the form
    */
    print "
        <div class=\"row\">
          <div class=\"col-lg-12\">
          <h2>PDF successfully generated</h2>
            <a href='mpdf_output/static_mpdf_test.pdf' target='_blank' class='btn btn-info'>View PDF</a>
            <a href='mpdf_files/style.css' target='_blank' class='btn btn-info'>View CSS</a>
            <a href='mpdf_files/theme_config.json' target='_blank' class='btn btn-success'>Download theme config</a>
            <p><br/></p>
          </div>
        </div>
    ";
  }
  
  /*
  * If $themesavail is set, we found at least one theme, so list them to load
  */
  if(isset($themesavail)) {
    HTML_load_theme_form($themesavail);
  }
  
  /*
  * Create file with theme, if we are happy with the results
  */  
  HTML_create_theme_form($FORM['Form']);
  
  /*
  * Create the form to edit mpdf values
  */
  HTML_print_form($FORM['Form']);
  
  HTML_print_end();
} else {
  /*
  * If $themesavail is set, we found at least one theme, so list them to load
  */
  if(isset($themesavail)) {
    HTML_load_theme_form($themesavail);
  }
  /*
  * Display the form
  */
  HTML_print_form();
}

/*
*************************************************************
* STARTING FUNCTIONS ****************************************
*************************************************************
*/

function create_theme_sample_pdf_file() {
  global $FORM;
  global $options;
  global $find;
  global $replace;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $stylecss = "@page {
  sheet-size: %PageWidth% %PageHeight%;  
  size: %PageWidth% %PageHeight%; 

  margin-left: %PageMarginLeft%; 
  margin-right: %PageMarginRight%;
  margin-top: %PageMarginTop%; 
  margin-bottom: %PageMarginBottom%;

  margin-footer: %PageMarginFooter%;
  odd-footer-name: html_footer-right;
  even-footer-name: html_footer-left;
  
  margin-header: %PageMarginHeader%;
  odd-header-name: html_header-right;
  even-header-name: html_header-left;         
}
";
  $stylecss .= file_get_contents('_assets/raw-theme/mpdf.css');
  $newstylecss = str_replace($find, $replace, $stylecss);
  file_put_contents("mpdf_files/style.css", $newstylecss);
  // html for sample mpdf
  $mpdfhtml = file_get_contents('_assets/raw-html/mpdf-body_themesample.html');
  $newmpdfhtml = str_replace($find, $replace, $mpdfhtml);
  file_put_contents("mpdf_files/body.html", $newmpdfhtml);
  // frontmatter for sample mpdf
  $mpdfhtml = file_get_contents('_assets/raw-html/frontmatter_themesample.html');
  $newmpdfhtml = str_replace($find, $replace, $mpdfhtml);
  file_put_contents("mpdf_files/frontmatter.html", $newmpdfhtml); 
    
  /*
  * Create sample.pdf using mpdf
  */
  // change the values to write the final PDF to the theme folder
  $options['mpdf_output'] = realpath(dirname(__FILE__))."/themes/".$FORM['Form']['Theme']['themenamefolder']."/static";
  $options['output'] = "sample.pdf";
  // Run mPDF
  include("static_booktype2mpdf.php");
  // copy sample.pdf to general pdf folder with samples
  chdir($options['home']);
  $option = system("cp ".$options['mpdf_output']."/".$options['output']." sample-files/".$FORM['Form']['Theme']['themenamefolder'].".pdf");
}
function create_theme_ebook_files() {
  global $FORM;
  global $options;
  global $find;
  global $replace;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  // copy css file to folder from which epub will be made
  exec("rm epub_files/btepub/OEBPS/Styles/theme.css");
  exec("cp ".$themenamefolder."/epub.css epub_files/btepub/OEBPS/Styles/theme.css");
  // delete all fonts, then copy new ones
  exec ("rm epub_files/btepub/OEBPS/Fonts/*");
  // copy the fonts to the static folder
  foreach($FORM['Fonts'] as $fontfamily => $values) {
    foreach($values as $style => $fontname) {
      if(file_exists($options['mpdf_lib']."ttfonts/".$fontname)) {
        exec("cp ".$options['mpdf_lib']."ttfonts/".$fontname." epub_files/btepub/OEBPS/Fonts/");
      }
    }
  }
  // make the new content.opf file with the fonts
  $content_opf = file_get_contents('_assets/raw-epub/content-start.opf');
  $content_opf = str_replace($find, $replace, $content_opf);
  // make the new toc.ncx file with the fonts
  $toc_ncx = file_get_contents('_assets/raw-epub/toc.ncx');
  $newtoc_ncx = str_replace($find, $replace, $toc_ncx);
  file_put_contents("epub_files/btepub/OEBPS/toc.ncx", $newtoc_ncx);   
  // add lines with font paths
  $counter = 0;
  foreach($FORM['Fonts'] as $fontfamily => $values) {
    foreach($values as $style => $fontname) {
      if(file_exists($options['mpdf_lib']."ttfonts/".$fontname)) {
        $content_opf .= "
  <item href=\"Fonts/".$fontname."\" id=\"static_".$counter++."\" media-type=\"application/octet-stream\"/>";
      }
    }
  }
  $content_opf .= file_get_contents('_assets/raw-epub/content-end.opf');
  // write new file
  file_put_contents("epub_files/btepub/OEBPS/content.opf", $content_opf);
  // make EPUB file
  chdir("epub_files/btepub/");
  $output = exec("rm ../btepub.epub");
  $output = exec("zip -0r btepub.zip mimetype OEBPS META-INF");
  $output = exec("mv btepub.zip ../btepub.epub");
  $output = exec($options['kindlegen']." ../btepub.epub");
  $output = exec("cp ../btepub.epub ".realpath(dirname(__FILE__))."/sample-files/".$FORM['Form']['Theme']['themenamefolder'].".epub");
  $output = exec("cp ../btepub.mobi ".realpath(dirname(__FILE__))."/sample-files/".$FORM['Form']['Theme']['themenamefolder'].".mobi");
}
function create_theme_epub_css() {
  global $FORM;
  global $find;
  global $replace;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  /*
  * the epub.css is a little different, because we need to add @font-face info
  */
  // create @font-face first
  $stylecss = "";
  foreach($FORM['Fonts']['families'] as $fontfamily => $values) {
    foreach($values as $style => $fontname) {
      // make sure the font exists
      if(file_exists($themenamefolder."/static/fonts/".$fontname)) {
        $stylecss .= "@font-face {";
        $stylecss .= "\n    font-family: '".$fontfamily."';";
        $stylecss .= "\n    font-weight: ";
        if($style == "R" OR $style == "I") {
          $stylecss .= "normal;";
        } else {
          $stylecss .= "bold;";
        }
        $stylecss .= "\n    font-style: ";
        if($style == "R" OR $style == "B") {
          $stylecss .= "normal;";
        } else {
          $stylecss .= "italic;";
        }
        $stylecss .= "\n    src: url('../Fonts/".$fontname."');";
        $stylecss .= "\n}\n\n";
      }
    }
  }
  // read epub CSS template file
  $stylecss .= file_get_contents($themenamefolder."/epub.css");
  $newstylecss = str_replace($find, $replace, $stylecss);
  // write template file
  file_put_contents($themenamefolder."/epub.css", $newstylecss);
}
function create_theme_editor_css() {
  global $FORM;
  global $find;
  global $replace;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $stylecss = file_get_contents($themenamefolder."/static/editor.css");
  $newstylecss = str_replace($find, $replace, $stylecss);
  // write template file
  file_put_contents($themenamefolder."/static/editor.css", $newstylecss);
}
function create_theme_screenpdf_css() {
  global $FORM;
  global $find;
  global $replace;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $stylecss = file_get_contents($themenamefolder."/screenpdf.css");
  $newstylecss = str_replace($find, $replace, $stylecss);
  // write template file
  file_put_contents($themenamefolder."/screenpdf.css", $newstylecss);
}
function create_theme_mpdf_css() {
  global $FORM;
  global $find;
  global $replace;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $stylecss = file_get_contents($themenamefolder."/mpdf.css");
  $newstylecss = str_replace($find, $replace, $stylecss);
  // write template file
  file_put_contents($themenamefolder."/mpdf.css", $newstylecss);
}
function create_theme_theme_fonts_php() {
  global $FORM;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $theme_fonts_txt = "/* '".$FORM['Form']['Theme']['themenamehuman']."' Version ".$FORM['Form']['Theme']['themeversion']." theme fonts */";
  foreach($FORM['Fonts']['families'] as $fontfamily => $values) {
    $theme_fonts_txt .= "\n        \"".$fontfamily."\" => array(";
    foreach($values as $style => $fontname) {
      $theme_fonts_txt .= "\n          '".$style."' => \"".$fontname."\",";
    }
    $theme_fonts_txt .= "\n        ),";
  }
  //print "<pre>"; print_r($FORM['Fonts']); print $theme_fonts_txt; print "</pre>";//???
  // write to file twice(?)
  file_put_contents($themenamefolder."/theme_fonts.php", $theme_fonts_txt);
  file_put_contents($themenamefolder."/static/theme_fonts.php", $theme_fonts_txt);
}
function create_theme_info_json() {
  global $FORM;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $info_json_txt = "{
  \"version\": \"".$FORM['Form']['Theme']['themeversion']."\",
  \"name\": \"".$FORM['Form']['Theme']['themenamehuman']."\",
  \"author\": \"".$FORM['Form']['Theme']['themeauthor']."\",
  \"date\": \"".$FORM['Form']['Theme']['themedate']."\",
  \"output\": {
    \"mpdf\": {
      \"options\": {},
      \"frontmatter\": \"frontmatter_mpdf.html\",
      \"endmatter\": \"endmatter_mpdf.html\",
      \"body\": \"body_mpdf.html\"
    },
    \"screenpdf\": {
      \"options\": {},
      \"frontmatter\": \"frontmatter_screenpdf.html\",
      \"endmatter\": \"endmatter_screenpdf.html\",
      \"body\": \"body_screenpdf.html\"
    },
    \"epub\": {
      \"assets\": {
        \"fonts\": [";
  $counter = 0;
  foreach($FORM['Fonts']['filenames'] as $fontfilename) {
    $counter++;
    $info_json_txt .= "
          \"static/fonts/".$fontfilename."\"";
    // add comma unless it is the last item,
    if(count($FORM['Fonts']['filenames']) > $counter) {
      $info_json_txt .= ",";
    }
  }
  $info_json_txt .= "\n        ]\n      }\n    }\n  }\n}";
  // write json to file
  file_put_contents($themenamefolder."/info.json", $info_json_txt);
}
function create_theme_panel_html() {
  global $FORM;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $theme_panel_html_txt = "{% load i18n %}
<div class=\"userstyle\">
  <div class=\"styleblock\">
    <div class=\"row\">
      <div class=\"col-md-12\">
       <p>{% trans \"".$FORM['Form']['Theme']['themedescription']."\" %} </p>";
  if(isset($FORM['Form']['Theme']['themeauthor']) && trim($FORM['Form']['Theme']['themeauthor']) != "") {
  $theme_panel_html_txt .= "
     <p>{% trans \"Author\" %}: ".$FORM['Form']['Theme']['themeauthor']."</p>";
  }
  if(isset($FORM['Form']['Theme']['themedate']) && trim($FORM['Form']['Theme']['themedate']) != "") {
  $theme_panel_html_txt .= "
     <p>{% trans \"Created\" %}: ".$FORM['Form']['Theme']['themedate']."</p>";
  }
  $linktofile = "http://files.sourcefabric.org/booktype/sample-files/".$FORM['Form']['Theme']['themenamefolder'].".pdf";
  $theme_panel_html_txt .= "
      <p>
        <span style='color: #fff; background-color: red; padding: 2px 5px; font-size: 0.7em; font-weight: bold;'><a href='".$linktofile."' target='_blank' style='color: #fff; text-decoration: none;'>PDF</a></span>
        <a href='".$linktofile."' target='_blank' style='text-decoration: none; font-size: 0.8em;'>Download sample PDF</a>
      </p>";
  $theme_panel_html_txt .= "
      </div>
    </div>
   </div>
</div>";
   file_put_contents($themenamefolder."/panel.html", $theme_panel_html_txt);
}
function create_theme_preload_css() {
  global $FORM;
  // folder where to write the file
  $themenamefolder = create_var_themenamefolder();
  $theme_preload_css_txt = "/* Pre-load '".$FORM['Form']['Theme']['themenamehuman']."' Version ".$FORM['Form']['Theme']['themeversion']." theme fonts */";
  foreach($FORM['Fonts']['families'] as $fontfamily => $values) {
    foreach($values as $style => $fontname) {
      // make sure the font exists
      if(file_exists($themenamefolder."/static/fonts/".$fontname)) {
        $theme_preload_css_txt .= "\n@font-face {";
        $theme_preload_css_txt .= "\n    font-family: '".$fontfamily."';";
        $theme_preload_css_txt .= "\n    src: url('fonts/".$fontname."') format('truetype');";
        $theme_preload_css_txt .= "\n    font-weight: ";
        if($style == "R" OR $style == "I") {
          $theme_preload_css_txt .= "normal;";
        } else {
          $theme_preload_css_txt .= "bold;";
        }
        $theme_preload_css_txt .= "\n    font-style: ";
        if($style == "R" OR $style == "B") {
          $theme_preload_css_txt .= "normal;";
        } else {
          $theme_preload_css_txt .= "italic;";
        }
        $theme_preload_css_txt .= "\n}";
      }
    }
  }
  file_put_contents($themenamefolder."/static/preload.css", $theme_preload_css_txt);
}
function create_var_themenamefolder() {
  global $FORM;
  global $options;
  $themenamefolder = $options['dirthemes']."/".$FORM['Form']['Theme']['themenamefolder']; 
  return $themenamefolder;
}

function create_bash_script($themesavail) {
  /*
  * this function is not needed anymore, I just kept the stump in here, in case I need the paths
  * for something else in the future.
  
  $bash = "";
  $bashconf = array();
  
  $bashconf['relpath']['themes'] = "/theme1/";
  $bashconf['relpath']['mpdfcss'] = "/theme1loc/";
  $bashconf['relpath']['infojson'] = "/theme1loc/";
  $bashconf['relpath']['panelhtml'] = "/theme1loc/";
  $bashconf['relpath']['screenpdfcss'] = "/theme1loc/";
  $bashconf['relpath']['editorcss'] = "/theme1loc/";
  $bashconf['relpath']['preloadcss'] = "/theme1loc/";
  $bashconf['relpath']['bodympdfhtml'] = "/theme1loc/";
  $bashconf['relpath']['endmatterscreenpdfhtml'] = "/theme1loc/";
  $bashconf['relpath']['frontmatterscreenpdfhtml'] = "/theme1loc/";
  $bashconf['relpath']['bodyscreenpdfhtml'] = "/theme1loc/";
  $bashconf['relpath']['frontmatterepubxhtml'] = "/theme1loc/";
  $bashconf['relpath']['endmattermpdfhtml'] = "/theme1loc/";
  $bashconf['relpath']['frontmattermpdfhtml'] = "/theme1loc/";
  
  $bashconf['locpath']['mpdfcss'] = "mpdf.css";
  $bashconf['locpath']['infojson'] = "info.json";
  $bashconf['locpath']['panelhtml'] = "panel.html";
  $bashconf['locpath']['screenpdfcss'] = "screenpdf.css";
  $bashconf['locpath']['editorcss'] = "static/editor.css";
  $bashconf['locpath']['preloadcss'] = "static/preload.css";
  $bashconf['locpath']['bodympdfhtml'] = "templates/body_mpdf.html";
  $bashconf['locpath']['endmatterscreenpdfhtml'] = "templates/endmatter_screenpdf.html";
  $bashconf['locpath']['frontmatterscreenpdfhtml'] = "templates/frontmatter_screenpdf.html";
  $bashconf['locpath']['bodyscreenpdfhtml'] = "templates/body_screenpdf.html";
  $bashconf['locpath']['frontmatterepubxhtml'] = "templates/frontmatter_epub.xhtml";
  $bashconf['locpath']['endmattermpdfhtml'] = "templates/endmatter_mpdf.html";
  $bashconf['locpath']['frontmattermpdfhtml'] = "templates/frontmatter_mpdf.html";
  
  foreach($themesavail as $themedir => $themename) {
    if(file_exists($themedir."/theme_config.json")) {
      $temp = json_decode(file_get_contents($themedir."/theme_config.json"), true);
      print "\n<br>folder: ".$temp['Theme']['themenamefolder'];//???
    }
  }
  */
}
/*
* Get directory tree recursively
*/
function dir_get_recursively($rootdir) {
  $return = array();
  $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootdir), RecursiveIteratorIterator::SELF_FIRST);
  foreach($objects as $dir => $object){
    $dir = rtrim($dir,".");
    $dir = rtrim($dir,"/");
    if(is_dir($dir)) {
      $return[$dir."/"] = $dir."/";
    }
  }
  asort($return);
  return $return;
}
/*
* Calculating absolute values for print font size in points (pt).
*/
function calc_fontsizes_mpdf($basefontsize) {
  $return = array();
  $return['0.33']   = round($basefontsize * 0.33, 1);
  $return['0.5']    = round($basefontsize * 0.5, 1);
  $return['0.6']    = round($basefontsize * 0.6, 1);
  $return['0.66']   = round($basefontsize * 0.66, 1);
  $return['0.7']   = round($basefontsize * 0.7, 1);
  $return['0.8']   = round($basefontsize * 0.8, 1);
  $return['0.9']   = round($basefontsize * 0.9, 1);
  $return['1']      = $basefontsize;
  $return['1.1']   = round($basefontsize * 1.1, 1);
  $return['1.2']   = round($basefontsize * 1.2, 1);
  $return['1.3']   = round($basefontsize * 1.3, 1);
  $return['1.33']   = round($basefontsize * 1.33, 1);
  $return['1.4']   = round($basefontsize * 1.4, 1);
  $return['1.5']    = round($basefontsize * 1.5, 1);
  $return['1.66']   = round($basefontsize * 1.66, 1);
  $return['1.8']   = round($basefontsize * 1.8, 1);
  $return['2']      = round($basefontsize * 2, 1);
  $return['2.33']   = round($basefontsize * 2.33, 1);
  $return['2.5']    = round($basefontsize * 2.5, 1);
  $return['2.66']   = round($basefontsize * 2.66, 1);
  $return['3']      = round($basefontsize * 3, 1);
  $return['3.33']   = round($basefontsize * 3.33, 1);
  $return['3.5']    = round($basefontsize * 3.5, 1);
  $return['3.66']   = round($basefontsize * 3.66, 1);
  $return['4']      = round($basefontsize * 4, 1);
  $return['4.33']   = round($basefontsize * 4.33, 1);
  $return['4.5']    = round($basefontsize * 4.5, 1);
  $return['4.66']   = round($basefontsize * 4.66, 1);
  $return['5']      = round($basefontsize * 5, 1);
  $return['5.5']    = round($basefontsize * 5.5, 1);
  $return['6']      = round($basefontsize * 6, 1);
  $return['6.5']    = round($basefontsize * 6.5, 1);
  $return['7']      = round($basefontsize * 7, 1);
  /**/
  //print "<pre>";print_r($return);print "</pre>"; //???
  ksort($return);
  return $return;
}
/*
* Calculating absolute values for line height in pt.
* Needs to be absolute to fake a baseline grid in mpdf
*/
function calc_lineheights_mpdf($caluclatedlineheight) {
  $return = array();
  $return['0'] = $return['0-top'] = $return['0-bottom'] = 0;
  $return['0.33-top'] = round(($caluclatedlineheight * 0.33), 1);
  $return['0.66-bottom'] = $caluclatedlineheight - $return['0.33-top'];
  $return['0.66-top'] = $return['0.66-bottom'];
  $return['0.33-bottom'] = $return['0.33-top'];
  $return['0.5-top'] = round(($caluclatedlineheight * 0.5), 1);
  $return['0.5-bottom'] = $caluclatedlineheight - $return['0.5-top'];
  $return['1'] = $return['1-top'] = $return['1-bottom'] = $caluclatedlineheight;
  $return['1.33-top'] = $return['1.33-bottom'] = $return['1.33'] = $return['1-top'] + $return['0.33-top'];
  $return['1.5-top'] = $return['1.5'] = $return['1-top'] + $return['0.5-top'];
  $return['1.5-bottom'] = $return['1-bottom'] + $return['0.5-bottom'];
  $return['1.66-bottom'] = $return['1.66-top'] = $return['1.66'] = $return['1-bottom'] + $return['0.66-bottom'];
  $return['2'] = $return['2-top'] = $return['2-bottom'] = ($caluclatedlineheight * 2);
  $return['2.33-top'] = $return['2.33-bottom'] = $return['2.33'] = $return['2-top'] + $return['0.33-top'];
  $return['2.5-top'] = $return['2.5'] = $return['2-top'] + $return['0.5-top'];
  $return['2.5-bottom'] = $return['2-bottom'] + $return['0.5-bottom'];
  $return['2.66-bottom'] = $return['2.66-top'] = $return['2.66'] = $return['2-bottom'] + $return['0.66-bottom'];
  $return['3'] = $return['3-top'] = $return['3-bottom'] = ($caluclatedlineheight * 3);
  $return['3.33-top'] = $return['3.33-bottom'] = $return['3.33'] = $return['3-top'] + $return['0.33-top'];
  $return['3.5-top'] = $return['3.5'] = $return['3-top'] + $return['0.5-top'];
  $return['4'] = $return['4-top'] = $return['4-bottom'] = ($caluclatedlineheight * 4);
  $return['4.5-top'] = $return['4.5'] = $return['4-top'] + $return['0.5-top'];
  $return['5'] = $return['5-top'] = $return['5-bottom'] = ($caluclatedlineheight * 5);
  $return['5.5-top'] = $return['5.5'] = $return['5-top'] + $return['0.5-top'];
  $return['6'] = $return['6-top'] = $return['6-bottom'] = ($caluclatedlineheight * 6);
  $return['6.5-top'] = $return['6.5'] = $return['6-top'] + $return['0.5-top'];
  $return['7'] = $return['7-top'] = $return['7-bottom'] = ($caluclatedlineheight * 7);
  /**/
  ksort($return);
  return $return;
}
/*
* Reading and interpreting the values from the post form
*/
function get_post_values() {
  global $_POST;
  global $formoptions; // the form values which are possible
  global $pagepresets;

  //print "<pre>"; print_r($_POST); print "</pre>";//???
  $return = array();
  // see if we got some information on a theme we need to create
  if(isset($_POST['themenamehuman'])) {
    $return['Theme']['themenamehuman'] = $_POST['themenamehuman'];
  }
  if(isset($_POST['themenamefolder'])) {
    $return['Theme']['themenamefolder'] = $_POST['themenamefolder'];
  }
  if(isset($_POST['themeversion'])) {
    $return['Theme']['themeversion'] = $_POST['themeversion'];
  }
  if(isset($_POST['themedescription'])) {
    $return['Theme']['themedescription'] = $_POST['themedescription'];
  }
  if(isset($_POST['themeauthor'])) {
    $return['Theme']['themeauthor'] = $_POST['themeauthor'];
  }
  if(isset($_POST['themedate'])) {
    $return['Theme']['themedate'] = $_POST['themedate'];
  }
  // fill the array with all the defaults that might come up, but empty
  foreach($formoptions as $group=>$values) {
    foreach($values['elements'] as $key => $value) {
      $return[$values['prefix'].$key] = $_POST[$values['prefix'].$key];
    }
  }
  return $return;
}
function calc_css_export_values($FORM) {
  global $formoptions; // the form values which are possible
  global $pagepresets;
  global $FontSizeEditorCSS;

  // first take the page size from the sent data if available - or set some defaults
  if(isset($pagepresets[$FORM['PageDefinition']]['cropwidth'])) {
    $return['CropWidth'] = $pagepresets[$FORM['PageDefinition']]['cropwidth'];
  } else {
    $return['CropWidth'] = "165mm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['cropheight'])) {
    $return['CropHeight'] = $pagepresets[$FORM['PageDefinition']]['cropheight'];
  } else {
    $return['CropHeight'] = "230mm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['width'])) {
    $return['PageWidth'] = $pagepresets[$FORM['PageDefinition']]['width'];
  } else {
    $return['PageWidth'] = "155mm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['height'])) {
    $return['PageHeight'] = $pagepresets[$FORM['PageDefinition']]['height'];
  } else {
    $return['PageHeight'] = "220mm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['marginleft'])) {
    $return['PageMarginLeft'] = $pagepresets[$FORM['PageDefinition']]['marginleft'];
  } else {
    $return['PageMarginLeft'] = "1.8cm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['marginright'])) {
    $return['PageMarginRight'] = $pagepresets[$FORM['PageDefinition']]['marginright'];
  } else {
    $return['PageMarginRight'] = "1.8cm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['margintop'])) {
    $return['PageMarginTop'] = $pagepresets[$FORM['PageDefinition']]['margintop'];
  } else {
    $return['PageMarginTop'] = "1.875cm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['marginbottom'])) {
    $return['PageMarginBottom'] = $pagepresets[$FORM['PageDefinition']]['marginbottom'];
  } else {
    $return['PageMarginBottom'] = "2.8cm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['marginfooter'])) {
    $return['PageMarginFooter'] = $pagepresets[$FORM['PageDefinition']]['marginfooter'];
  } else {
    $return['PageMarginFooter'] = "10mm";
  }
  if(isset($pagepresets[$FORM['PageDefinition']]['marginheader'])) {
    $return['PageMarginHeader'] = $pagepresets[$FORM['PageDefinition']]['marginheader'];
  } else {
    $return['PageMarginHeader'] = "10mm";
  }
  
  // see if we have any theme values we could use
  if(isset($FORM['Theme']) && isset($FORM['Theme']['themenamehuman'])) {
    $return['ThemeNameHumanVal'] = $FORM['Theme']['themenamehuman'];
    $return['ThemeNameFolderVal'] = $FORM['Theme']['themenamefolder'];
    $return['ThemeVersionVal'] = $FORM['Theme']['themeversion'];
    $return['ThemeDescriptionVal'] = $FORM['Theme']['themedescription'];
  }
  
  // calculate the line height depending on font size and relative factor from form select
  $caluclatedlineheight = round((floatval($FORM['BodyFontSize']) * $FORM['BodyLineHeight']), 1);
  /*
  * Run this foreach to get the values in _POST matching the form variables we asked for
  */
  foreach($formoptions as $group=>$values) {
    foreach($values['elements'] as $key => $value) {
      // the values we assign to the template finish with "Val". 
      $return[$values['prefix'].$key."Val"] = $FORM[$values['prefix'].$key];
      // now we do the edge cases where the posted value is not what the CSS needs
      // first, if the font family says: "inherit", use the font for the body 
      if($FORM[$values['prefix'].$key] == "inherit") {
        $return[$values['prefix'].$key."Val"] = $FORM['BodyFontFamily'];
      }
      if( // calculate line height
        $values['prefix'].$key == "BodyLineHeight"         
      ) {
        $return['BodyLineHeightVal'] = $caluclatedlineheight;
      }
      if( // border bottom yes, no, how strong?
        $key == "BorderBottom"
      ) {
        if($FORM[$values['prefix'].$key] == "thin") {
          $return[$values['prefix'].$key."Val"] = "0.05rem";
        } elseif($FORM[$values['prefix'].$key] == "medium") {
          $return[$values['prefix'].$key."Val"] = "0.2rem";
        } elseif($FORM[$values['prefix'].$key] == "thick") {
          $return[$values['prefix'].$key."Val"] = "0.8rem";
        }else {
          $return[$values['prefix'].$key."Val"] = "0rem";
        }
      }
      if( // quote uses line on the left
        $key == "LineLeft"
      ) {
        if($FORM[$values['prefix'].$key] == "thin") {
          $return[$values['prefix'].$key."Val"] = "0.05rem";
        } elseif($FORM[$values['prefix'].$key] == "light") {
          $return[$values['prefix'].$key."Val"] = "0.2rem";
        } elseif($FORM[$values['prefix'].$key] == "medium") {
          $return[$values['prefix'].$key."Val"] = "0.5rem";
        } elseif($FORM[$values['prefix'].$key] == "thick") {
          $return[$values['prefix'].$key."Val"] = "1rem";
        }else {
          $return[$values['prefix'].$key."Val"] = "0rem";
        }
      }
      if( // quote uses line on the left
        $key == "LineLeftColor"
      ) {
        if($FORM[$values['prefix'].$key] == "white") {
          $return[$values['prefix'].$key."Val"] = "#fff";
        } elseif($FORM[$values['prefix'].$key] == "light-grey") {
          $return[$values['prefix'].$key."Val"] = "#ccc";
        } elseif($FORM[$values['prefix'].$key] == "dark-grey") {
          $return[$values['prefix'].$key."Val"] = "#999";
        } elseif($FORM[$values['prefix'].$key] == "black") {
          $return[$values['prefix'].$key."Val"] = "#000";
        }else {
          $return[$values['prefix'].$key."Val"] = "0rem";
        }
      }
      if( // font size for header or footer
        $values['prefix'].$key == "HeaderFontSize" OR
        $values['prefix'].$key == "FooterFontSize"         
      ) {
        if($FORM[$values['prefix'].$key] == "tiny") {
          $return[$values['prefix'].$key."Val"] = "0.5rem";
        } elseif($FORM[$values['prefix'].$key] == "small") {
          $return[$values['prefix'].$key."Val"] = "0.8rem";
        }else {
          $return[$values['prefix'].$key."Val"] = "1rem";
        }
      }
      if( // show or hide line for header or footer
        $values['prefix'].$key == "HeaderLine" OR
        $values['prefix'].$key == "FooterLine"         
      ) {
        if($FORM[$values['prefix'].$key] == "true") {
          $return[$values['prefix'].$key."Val"] = "0.05rem";
        }else {
          $return[$values['prefix'].$key."Val"] = "0rem";
        }
      }
      if( // show line on title page in front matter
        $values['prefix'].$key == "FrontmatterTitlepageLine"
      ) {
        if($FORM[$values['prefix'].$key] == "true") {
          $return[$values['prefix'].$key."Val"] = "0.3rem";
        }else {
          $return[$values['prefix'].$key."Val"] = "0rem";
        }
      }
      if( // space between paragraphs
        $values['prefix'].$key == "BodyParagraphSpacing"
      ) {
        if($FORM[$values['prefix'].$key] == "none") {
          $return[$values['prefix'].$key."Val"] = "0";
          // Special values for the epub CSS
          $return['EpubBodyParagraphSpacingEmVal'] = 0.15; 
          // Special values for the editor CSS
          $return['EditorBodyParagraphSpacingEmVal'] = 0.18;
        }else {
          $return[$values['prefix'].$key."Val"] = $caluclatedlineheight;
          // Special values for the epub CSS
          $return['EpubBodyParagraphSpacingEmVal'] = 0.7; 
          // Special values for the editor CSS
          $return['EditorBodyParagraphSpacingEmVal'] = 0.7;
        }
      }
    }
  }
  $lineheights = calc_lineheights_mpdf($return['BodyLineHeightVal']);
  $fontsizes = calc_fontsizes_mpdf(floatval($return['BodyFontSizeVal']));
  if($debug == "true") {
    print "<pre>Line heights: \n"; print_r($lineheights);print "</pre>";
    print "<pre>Font sizes: \n"; print_r($fontsizes);print "</pre>";
  }
  
  // indents in paragraphs
  if($FORM['BodyParagraphIndent'] == "none") {
    $return['BodyParagraphIndentVal'] = "0pt"; 
    // Special values for the epub CSS
    $return['EpubBodyParagraphIndentEmVal'] = 0; 
    // Special values for the editor CSS
    $return['EditorBodyParagraphIndentEmVal'] = 0;
  
    /*
    * CSS values for hanging paragraphs 
    * (first line is not indented, rest of paragraph is indented.)
    * There are no indentations for paragraphs in theme, so select value 2 for hanging.
    */
    $return['BodyParagraphHangingVal'] = $lineheights[2]."pt"; 
    // Special values for the epub CSS
    $return['EpubBodyParagraphHangingEmVal'] = 2;
    // Special values for the editor CSS
    $return['EditorBodyParagraphHangingEmVal'] = 2;
    
  } else {
    /*
    * take an absolute, no rem value to match other indents in paragraphs 
    * with different font-sizes.
    */
    $return['BodyParagraphIndentVal'] = $lineheights[$FORM['BodyParagraphIndent']]."pt"; 
    // Special values for the epub CSS
    $return['EpubBodyParagraphIndentEmVal'] = $FORM['BodyParagraphIndent']; 
    // Special values for the editor CSS
    $return['EditorBodyParagraphIndentEmVal'] = $FORM['BodyParagraphIndent'];
  
    /*
    * CSS values for hanging paragraphs 
    * (first line is not indented, rest of paragraph is indented.)
    * Use the same values as the indentation for paragraphs were selected.
    */
    $return['BodyParagraphHangingVal'] = $lineheights[$FORM['BodyParagraphIndent']]."pt"; 
    // Special values for the epub CSS
    $return['EpubBodyParagraphHangingEmVal'] = $FORM['BodyParagraphIndent'];
    // Special values for the editor CSS
    $return['EditorBodyParagraphHangingEmVal'] = $FORM['BodyParagraphIndent'];
  }

  /*
  * Dropcaps - first character in chapter
  * To keep the baseline grid intact, they must be $lineheights['3.33'] - which works well enough across fonts and sizes
  */
  if($FORM['BodyParagraphDropcaps'] == "false") {
    // set font size and line height to default
    $return['BodyParagraphDropcapsFontsizeEmVal'] = "inherit";
    $return['BodyParagraphDropcapsFontsizePtVal'] = "inherit";
  } else {
    // set font size and line height to value posted
    $return['BodyParagraphDropcapsFontsizeEmVal'] = "2";
    $return['BodyParagraphDropcapsFontsizePtVal'] = $lineheights['3.33'];
  }
  /*
  * Calculate some extra values for specific elements from $_POST
  * Quite a few for all headlines, needed to calculate distances in print, browser editor and epub
  */
  $secondround = array(
    "ChapterHeader1", "BodyHeader1", "BodyHeader2", "BodyHeader3", "BodyHeader4", "BodyHeader5", 
  );
  foreach($secondround as $item) {
    $temp = explode(":",$FORM[$item.'Padding']);
    //print "<pre>"; print_r($temp); print "\no:".$lineheights[$temp[0]]."\n1:".$lineheights[$temp[1]]; print "</pre>";//???
    $return[$item.'PaddingTopVal'] = $lineheights[$temp[0]];
    $return[$item.'PaddingBottomVal'] = $lineheights[$temp[1]];
    $return[$item.'PaddingTopEmVal'] = calc_editor_css_values(floatval($temp[0])); // used in epub.css
    $return[$item.'PaddingBottomEmVal'] = calc_editor_css_values(floatval($temp[1])); // used in epub.css
    $return[$item.'FontSizeRemVal'] = $return[$item.'FontSizeVal']; // REM (could be) used for mpdf CSS
    $return[$item.'LineHeightRemVal'] = $return[$item.'LineHeightVal']; // REM (could be) used for mpdf CSS
    $return[$item.'FontSizeEmVal'] = calc_editor_css_values($return[$item.'FontSizeVal']); // EM (should be) used for browser's editor CSS  
    $return[$item.'LineHeightEmVal'] = calc_editor_css_values($return[$item.'LineHeightVal']); // EM (should be) used for browser's editor CSS  
    $return[$item.'FontSizePtVal'] = $fontsizes[$return[$item.'FontSizeVal']]; // Points used in mpdf CSS
    $return[$item.'LineHeightPtVal'] = $lineheights[$return[$item.'LineHeightVal']]; // Points used in mpdf CSS
  }
  
  // special values for caption of figures, tables, etc.
  $return['CaptionFontSizePtVal'] = $fontsizes["0.9"]; // Points used in mpdf CSS
  $return['CaptionFontSizeRemVal'] = "0.9"; // REM (could be) used for mpdf CSS
  $return['CaptionFontSizeEmVal'] = calc_editor_css_values("0.9"); // EM (should be) used for browser's editor CSS
  
  // Part or Section, the bigger chunks that contain a number of chapters
  $return['PartFontSizeEmVal'] = calc_editor_css_values($return['PartFontSizeVal']); // EM (should be) used for browser's editor CSS  
  $return['PartFontSizePtVal'] = $fontsizes[$return['PartFontSizeVal']]; // Points used in mpdf CSS
  // Front matter
  $return['FrontmatterHalftitleTitleFontSizeEmVal'] = calc_editor_css_values($return['FrontmatterHalftitleTitleFontSizeVal']); // EM (should be) used for browser's editor CSS  
  $return['FrontmatterHalftitleTitleFontSizePtVal'] = $fontsizes[$return['FrontmatterHalftitleTitleFontSizeVal']]; // Points used in mpdf CSS
  $return['FrontmatterHalftitleAuthorFontSizeEmVal'] = calc_editor_css_values($return['FrontmatterHalftitleAuthorFontSizeVal']); // EM (should be) used for browser's editor CSS  
  $return['FrontmatterHalftitleAuthorFontSizePtVal'] = $fontsizes[$return['FrontmatterHalftitleAuthorFontSizeVal']]; // Points used in mpdf CSS
  $return['FrontmatterTitlepageTitleFontSizeRemVal'] = $return['FrontmatterTitlepageTitleFontSizeVal']; // REM (could be) used for mpdf CSS
  $return['FrontmatterTitlepageTitleFontSizeEmVal'] = calc_editor_css_values($return['FrontmatterTitlepageTitleFontSizeVal']); // EM (should be) used for browser's editor CSS  
  $return['FrontmatterTitlepageTitleFontSizePtVal'] = $fontsizes[$return['FrontmatterTitlepageTitleFontSizeVal']]; // Points used in mpdf CSS
  $return['FrontmatterTitlepageSubtitleFontSizeEmVal'] = calc_editor_css_values($return['FrontmatterTitlepageSubtitleFontSizeVal']); // EM (should be) used for browser's editor CSS  
  $return['FrontmatterTitlepageSubtitleFontSizePtVal'] = $fontsizes[$return['FrontmatterTitlepageSubtitleFontSizeVal']]; // Points used in mpdf CSS
  $return['FrontmatterTitlepageAuthorFontSizeEmVal'] = calc_editor_css_values($return['FrontmatterTitlepageAuthorFontSizeVal']); // EM (should be) used for browser's editor CSS  
  $return['FrontmatterTitlepageAuthorFontSizePtVal'] = $fontsizes[$return['FrontmatterTitlepageAuthorFontSizeVal']]; // Points used in mpdf CSS
  
  // Quote
  $temp = explode(":",$FORM['QuotePadding']);
  $return['QuotePaddingTopVal'] = $lineheights[$temp[0]];
  $return['QuotePaddingBottomVal'] = $lineheights[$temp[1]];
  $return['QuoteFontSizeRemVal'] = $return['QuoteFontSizeVal']; // REM (could be) used for mpdf CSS
  $return['QuoteLineHeightRemVal'] = $return['QuoteLineHeightVal']; // REM (could be) used for mpdf CSS
  $return['QuoteFontSizeEmVal'] = calc_editor_css_values($return['QuoteFontSizeVal']); // EM (should be) used for browser's editor CSS  
  $return['QuoteLineHeightEmVal'] = calc_editor_css_values($return['QuoteLineHeightVal']); // EM (should be) used for browser's editor CSS  
  $return['QuoteFontSizePtVal'] = $fontsizes[$return['QuoteFontSizeVal']]; // Points used in mpdf CSS
  $return['QuoteLineHeightPtVal'] = $lineheights[$return['QuoteLineHeightVal']]; // Points used in mpdf CSS
  // padding from margin on left to line which is left of quote
  $return['QuotePaddingMargin2LinePtVal'] = $lineheights[$FORM['QuotePaddingMargin2Line']];
  $return['QuotePaddingMargin2LineEmVal'] = $FORM['QuotePaddingMargin2Line'];
  $return['QuotePaddingLine2QuotePtVal'] = $lineheights[$FORM['QuotePaddingLine2Quote']];
  $return['QuotePaddingLine2QuoteEmVal'] = $FORM['QuotePaddingLine2Quote'];
  
  // Special values for the editor CSS
  $return['EditorBodyFontSizeVal'] = $FontSizeEditorCSS[$return['BodyFontSizeVal']]; 

  /*
  * Change header and footer
  * <div class="header-text" style="text-align: left;">{{ title }}</div>
  * <div class="footer-text" style="text-align: center;">{PAGENO}</div> 
  */
  $return['HeaderHtmlLeftVal'] = $return['HeaderHtmlRightVal'] = "";
  $return['FooterHtmlLeft'] = $return['FooterHtmlRight'] = "";
  // Change header
  $return['HeaderDisplayVal'] = "none";
  if($FORM['HeaderDisplay'] == "show") {
    $return['HeaderDisplayVal'] = "block";
    $return['HeaderHtmlLeftVal'] = $return['HeaderHtmlRightVal'] = "<div class=\"header-text\" style=\"text-align: ";
    if($FORM['HeaderAlign'] == "inside") {
      $alignleft = "right";
      $alignright = "left";
      $return['HeaderLeftAlignVal'] = "right";
      $return['HeaderRightAlignVal'] = "left";
    } elseif($FORM['HeaderAlign'] == "outside") {
      $alignleft = "left";
      $alignright = "right";
      $return['HeaderLeftAlignVal'] = "left";
      $return['HeaderRightAlignVal'] = "right";
    } else {
      $alignleft = $alignright = "center";
      $return['HeaderLeftAlignVal'] = "center";
      $return['HeaderRightAlignVal'] = "center";
    }
    $return['HeaderHtmlLeftVal'] .= $alignleft.";\">";
    $return['HeaderHtmlRightVal'] .= $alignright.";\">";
    if($FORM['HeaderContent'] == "booktitle") {
      $return['HeaderHtmlLeftVal'] .= "{{ title }}";
      $return['HeaderHtmlRightVal'] .= "{{ title }}";
    } elseif($FORM['HeaderContent'] == "pagenumber") {
      $return['HeaderHtmlLeftVal'] .= "{PAGENO}";
      $return['HeaderHtmlRightVal'] .= "{PAGENO}";
    } 
    $return['HeaderHtmlLeftVal'] .= "</div>";
    $return['HeaderHtmlRightVal'] .= "</div>";
  }
  // Change footer
  $return['FooterDisplayVal'] = "none";
  if($FORM['FooterDisplay'] == "show") {
    $return['FooterDisplayVal'] = "block";
    $return['FooterHtmlLeftVal'] = $return['FooterHtmlRightVal'] = "<div class=\"footer-text\" style=\"text-align: ";
    if($FORM['FooterAlign'] == "inside") {
      $alignleft = "right";
      $alignright = "left";
      $return['FooterLeftAlignVal'] = "right";
      $return['FooterRightAlignVal'] = "left";
    } elseif($FORM['FooterAlign'] == "outside") {
      $alignleft = "left";
      $alignright = "right";
      $return['FooterLeftAlignVal'] = "left";
      $return['FooterRightAlignVal'] = "right";
    } else {
      $alignleft = $alignright = "center";
      $return['FooterLeftAlignVal'] = "center";
      $return['FooterRightAlignVal'] = "center";
    }
    $return['FooterHtmlLeftVal'] .= $alignleft.";\">";
    $return['FooterHtmlRightVal'] .= $alignright.";\">";
    if($FORM['FooterContent'] == "booktitle") {
      $return['FooterHtmlLeftVal'] .= "{{ title }}";
      $return['FooterHtmlRightVal'] .= "{{ title }}";
    } elseif($FORM['FooterContent'] == "pagenumber") {
      $return['FooterHtmlLeftVal'] .= "{PAGENO}";
      $return['FooterHtmlRightVal'] .= "{PAGENO}";
    } 
    $return['FooterHtmlLeftVal'] .= "</div>";
    $return['FooterHtmlRightVal'] .= "</div>";
  }

  ksort($return);
  return $return;
}
/*
* Calculate good display sizes from relative sizes for print.
* Usually, the relative sizes come across too big in the online editor.
*/
function calc_editor_css_values($relsize) {
  $factor = 1; // this is the factor by which the size is reduced
  // change factor according to original size - the larger the original, the more we scale down
  if($relsize >= 1.5) { $factor = 0.9; }
  if($relsize >= 2) { $factor = 0.8; }
  if($relsize >= 3) { $factor = 0.7; }
  if($relsize >= 4) { $factor = 0.6; }
  if($relsize >= 5) { $factor = 0.5; }
  $return = round(($factor * $relsize), 2);

  return $return;
}

/*
* Create from to select and load a theme
*/
function HTML_load_theme_form($themesavail) {
  global $_POST;
  print "
    <form role=\"form\" data-toggle=\"validator\" action=\"\" method=\"post\" class=\"form-horizontal\">";
  print "
      <div class=\"row\">
        <div class=\"col-lg-12\">";
  print "
        <fieldset>
        
        <!-- Form Name -->
        <legend>Do something, like loading a theme or creating files</legend>
        
        <!-- Select theme-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"SelectAction\">Select action</label>  
          <div class=\"col-md-6\">
              <select id=\"SelectAction\" name=\"SelectAction\" class=\"form-control\">
                <option value=\"LoadTheme\""; if($_POST['SelectAction'] == "LoadTheme") { print " selected"; } print ">Load theme (select theme below)</option>
                <option value=\"CreateFontConfigFile\""; if($_POST['SelectAction'] == "CreateFontConfigFile") { print " selected"; } print ">Create config file with fonts for mpdf</option>
            </select>
          </div>
        </div>       
        
        <!-- Select theme-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"SelectTheme\">Select theme (if needed)</label>  
          <div class=\"col-md-6\">
              <select id=\"SelectTheme\" name=\"SelectTheme\" class=\"form-control\">";
          foreach($themesavail as $themedir => $themename) {
            print "\n      <option value=\"$themedir\""; if($_POST['SelectTheme'] == $themedir) { print " selected"; } print ">$themename</option>";    
          }
          print "
            </select>
          </div>
        </div>       
        <!-- Multiple Radios (inline) -->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"radios\">Create theme on loading (only if loading)</label>
          <div class=\"col-md-6\"> 
            <label class=\"radio-inline\" for=\"radios-0\">
              <input name=\"createfilesonloading\" id=\"radios-0\" value=\"true\" type=\"radio\">
              yes
            </label> 
            <label class=\"radio-inline\" for=\"radios-1\">
              <input name=\"createfilesonloading\" id=\"radios-1\" value=\"false\" type=\"radio\" checked=\"checked\" >
              no
            </label> 
          <span class=\"help-block\">If you select to load a theme, this option triggers (on loading) to create the complete theme with all files in theme folder and sample pdf, epub, mobi in folder 'sample-files'.</span>  
          </div>
        </div> 
        </fieldset>
        ";
  print "
        <!-- Submit button-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"Action\"></label>  
          <div class=\"col-md-6\">
            <button type=\"submit\" name=\"Action\" value=\"dosomething\" class=\"btn btn-info\">Make it happen</button>
          </div>
        </div>
        </form>
        ";

  print "
          <p><br/></p>
        </div>
      </div>
  ";
}
/*
* Create theme from form values
*/
function HTML_create_theme_form($FORM) {
  print "
    <form role=\"form\" data-toggle=\"validator\" action=\"\" method=\"post\" class=\"form-horizontal\">";
  /*
  * If theme is being edited, add theme values as hidden items to form
  */
  if(isset($FORM['Theme'])) {
    foreach($FORM['Theme'] as $key => $value) {
      print "
      <input type hidden name=\"".$key."\" value =\"".$value."\">";
    }
  }
  /*
  * Write all values from the form
  */
  foreach($FORM as $key => $value) {
    print "
      <input type hidden name=\"".$key."\" value =\"".$value."\">";
  }
  print "
      <div class=\"row\">
        <div class=\"col-lg-12\">";
  print "
        <fieldset>
        
        <!-- Form Name -->
        <legend>";
  if(isset($FORM['Theme'])) {
    print "Save changes to theme or add new values to create a new theme";
  } else {
    print "Create new theme from these settings";
  } 
  print "
        </legend>
        <!-- Text input-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"themenamehuman\">Human readable name</label>  
          <div class=\"col-md-6\">
          <input ";
  if(isset($FORM['Theme']['themenamehuman']) && trim($FORM['Theme']['themenamehuman']) != "") {
    print "value=\"".trim($FORM['Theme']['themenamehuman'])."\" ";
  }
  print "id=\"themenamehuman\" name=\"themenamehuman\" placeholder=\"e.g. Fairy Tale\" class=\"form-control input-md\" required=\"\" type=\"text\">
          </div>
        </div>
        
        <!-- Text input-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"themenamefolder\">Folder name</label>  
          <div class=\"col-md-6\">
          <input ";
  if(isset($FORM['Theme']['themenamefolder']) && trim($FORM['Theme']['themenamefolder']) != "") {
    print "value=\"".trim($FORM['Theme']['themenamefolder'])."\" ";
  }
  print "id=\"themenamefolder\" name=\"themenamefolder\" placeholder=\"e.g. fairytale\" class=\"form-control input-md\" required=\"\" type=\"text\">
          </div>
        </div>
        
        <!-- Text input-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"themeversion\">Theme version</label>  
          <div class=\"col-md-6\">
          <input ";
  if(isset($FORM['Theme']['themeversion']) && trim($FORM['Theme']['themeversion']) != "") {
    print "value=\"".trim($FORM['Theme']['themeversion'])."\" ";
  }
  print "id=\"themeversion\" name=\"themeversion\" placeholder=\"e.g. 0.1\" class=\"form-control input-md\" required=\"\" type=\"text\">
          </div>
        </div>
        
        <!-- Text input-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"themedescription\">Description</label>  
          <div class=\"col-md-6\">
          <input ";
  if(isset($FORM['Theme']['themedescription']) && trim($FORM['Theme']['themedescription']) != "") {
    print "value=\"".trim($FORM['Theme']['themedescription'])."\" ";
  }
  print "id=\"themedescription\" name=\"themedescription\" placeholder=\"e.g. A romantic theme for novels\" class=\"form-control input-md\" required=\"\" type=\"text\">
          </div>
        </div>
        <!-- Text input-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"themeauthor\">Author</label>  
          <div class=\"col-md-6\">
          <input ";
  if(isset($FORM['Theme']['themeauthor']) && trim($FORM['Theme']['themeauthor']) != "") {
    print "value=\"".trim($FORM['Theme']['themeauthor'])."\" ";
  }
  print "id=\"themeauthor\" name=\"themeauthor\" placeholder=\"e.g. Autor van Book\" class=\"form-control input-md\" required=\"\" type=\"text\">
          </div>
        </div>
        <!-- Text input-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"themedate\">Creation date</label>  
          <div class=\"col-md-6\">
          <input ";
  if(isset($FORM['Theme']['themedate']) && trim($FORM['Theme']['themedate']) != "") {
    print "value=\"".trim($FORM['Theme']['themedate'])."\" ";
  }
  print "id=\"themedate\" name=\"themedate\" placeholder=\"e.g. 1st June 2016\" class=\"form-control input-md\" required=\"\" type=\"text\">
          </div>
        </div>
        
        </fieldset>";
  print "
        <!-- Submit button-->
        <div class=\"form-group\">
          <label class=\"col-md-6 control-label\" for=\"Action\"></label>  
          <div class=\"col-md-6\">
            <button type=\"submit\" name=\"Action\" value=\"createtheme\" class=\"btn btn-info\">Save or Create Theme</button>
          </div>
        </div>
        </form>
        ";

  print "
          <p><br/></p>
        </div>
      </div>
  ";
}
/*
* HTML to create the form
*/
function HTML_print_form($FORM) {
  global $formvals;
  global $pagepresets;
  global $formoptions;
  global $fontfamilies;
  
  // if there were no posted values, still don't start with the default paper size which is the smallest
  if(!isset($FORM['PageDefinition'])) { $FORM['PageDefinition'] = "bodde__155_x_220_mm"; }
  if(!isset($FORM['BodyFontSize'])) { $FORM['BodyFontSize'] = "10pt"; }
  if(!isset($FORM['BodyLineHeight'])) { $FORM['BodyLineHeight'] = "1.4"; }
  if(!isset($FORM['BodyFontFamily'])) { $FORM['BodyFontFamily'] = "texgyreschola"; }
  if(!isset($FORM['FooterFontSize'])) { $FORM['FooterFontSize'] = "small"; }
  if(!isset($FORM['HeaderFontSize'])) { $FORM['HeaderFontSize'] = "small"; }
  // all font sizes set to em = 1
  $fontsizesprefix = array("Quote", "Header", "Footer", "Part", "FrontmatterHalftitleTitle",
    "FrontmatterTitlepageTitle", "FrontmatterTitlepageSubtitle", "FrontmatterTitlepageAuthor",
    "FrontmatterHalftitleAuthor", "ChapterHeader1", "BodyHeader1", "BodyHeader2", "BodyHeader3", 
    "BodyHeader4", "BodyHeader5");
  foreach($fontsizesprefix as $prefix) {
    if(!isset($FORM[$prefix.'FontSize'])) { $FORM[$prefix.'FontSize'] = "1"; }
  }
  print "
      <div class=\"row\">
        <div class=\"col-lg-12\">
        <h2>Configure theme layout</h2>
        </div>
      </div>
  ";

  print "<form role=\"form\" data-toggle=\"validator\" action=\"\" method=\"post\" class=\"form-horizontal\">
  ";
  print "
<!-- Submit button-->
<div class=\"form-group\">
  <label class=\"col-md-6 control-label\" for=\"Submit\"></label>  
  <div class=\"col-md-6\">
    <button type=\"submit\" name=\"Action\" value=\"submitform\" class=\"btn btn-info\">Create PDF</button>
  </div>
</div>";
  /*
  * If theme is being edited, add theme values as hidden items to form
  */
  if(isset($FORM['Theme'])) {
    foreach($FORM['Theme'] as $key => $value) {
      print "
      <input type hidden name=\"".$key."\" value =\"".$value."\">";
    }
  }
  /*
  * Start writing the form element by element
  */
  $checknewgroup = ""; // if we enter a new group in the form, write fieldset with legend to make it easier to read
  foreach($formoptions as $group=>$values) {
    foreach($values['elements'] as $key => $value) {
      // do we need to write a fieldset to make form easier to read?
      if($checknewgroup != $values['prefix']) {
        // first time here? If not, close fieldset
        if( $checknewgroup != "") {
        print "
        </fieldset>";
        }
        print "
        <fieldset>
          <legend>".$values['prefix']."</legend>";
        $checknewgroup = $values['prefix'];
      }
    print "
<div class=\"form-group\">";
    if( // show a pulldown instead of text fields with measurements and margins
      $key == "varPaperWidth") {
    print "
  <label class=\"col-md-6 control-label\" for=\"PageDefinition\">Page Definition</label>";
    } else {
    print "
  <label class=\"col-md-6 control-label\" for=\"$key\">$key</label>";
    }
    print "
  <div class=\"col-md-6\">";
      $temp = explode("__", $value); // break string into array to see if select, radio etc. and what values
      if($temp[0] == "select") {
        if($temp[1] == "PageDefinition") {
          print "
          <select id=\"PageDefinition\" name=\"PageDefinition\" class=\"form-control\">";
          foreach($pagepresets as $key => $pagepreset) {
            print "\n      <option value=\"$key\""; if($FORM['PageDefinition'] == $key) { print " selected"; } print ">$key</option>";    
          }
          print "
          </select>";
        } elseif($temp[1] == "FontFamily") {
          print "
          <select id=\"".$values['prefix']."FontFamily\" name=\"".$values['prefix']."FontFamily\" class=\"form-control\">";
          // for body we must chose a FontFamily. For the others, offer the option to use "Default body font"
          if($values['prefix'] != "Body") {
            print "\n      <option value=\"inherit\""; if($FORM[$values['prefix'].$key] == "inherit") { print " selected"; } print ">Use default body font or select:</option>";
          }
          foreach($fontfamilies as $fontfamily => $filenames) {
            print "\n      <option value=\"$fontfamily\""; if($FORM[$values['prefix'].$key] == $fontfamily) { print " selected"; } print ">$fontfamily</option>";    
          }
          print "
          </select>";
        } else {
          // take select values from $temp[1] which was taken from array values string for elements
          $selectvals = explode("_", $temp[1]);
          print "
          <select id=\"".$values['prefix'].$key."\" name=\"".$values['prefix'].$key."\" class=\"form-control\">";
          foreach($selectvals as $selectval) {
            print "\n      <option value=\"$selectval\""; if($FORM[$values['prefix'].$key] == $selectval) { print " selected"; } print ">$selectval</option>";    
          }
          print "
          </select>";
        }
      } elseif($temp[0] == "radio") {
        // take select values from $temp[1] which was taken from array values string for elements
        $selectvals = explode("_", $temp[1]);
        foreach($selectvals as $selectval) {
          print "
          <label class=\"radio-inline\" for=\"".$values['prefix'].$key."-".$selectval."\">
            <input name=\"".$values['prefix'].$key."\" id=\"".$values['prefix'].$key."-".$selectval."\" value=\"".$selectval."\""; 
            if($value == "true") { print " checked=\"checked\" "; } 
          print " type=\"radio\">".$selectval."
          </label>";     
        }
      }
    print "
  </div>
</div>
";  
    }
  }
  // end of form
  print "
<!-- Submit button-->
<div class=\"form-group\">
  <label class=\"col-md-6 control-label\" for=\"Submit\"></label>  
  <div class=\"col-md-6\">
    <button type=\"submit\" name=\"Action\" value=\"submitform\" class=\"btn btn-info\">Create PDF</button>
  </div>
</div>
</form>
";
  HTML_print_end();
}
/*
* HTML for the header and body tag
*/
function HTML_print_start() {
  print "
<!DOCTYPE html>
<html lang=\"en\">
  <head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <title>Booktype mPDF Theme Maker</title>

    <!-- Latest compiled and minified CSS -->
    <link rel=\"stylesheet\" href=\"_assets/bootstrap/css/bootstrap3.3.6.min.css\">
    
    <!-- Latest compiled and minified JavaScript -->
    <script src=\"_assets/js/jquery-1.11.3.min.js\"></script>
    <script src=\"_assets/bootstrap/js/bootstrap3.3.6.min.js\"></script>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src=\"_assets/bootstrap/js/html5shiv3.7.2.min.js\"></script>
      <script src=\"_assets/bootstrap/js/respond1.4.2.min.js\"></script>
    <![endif]-->
  </head>
  <body>
    <div class=\"container\">
      <div class=\"row\">
        <div class=\"col-lg-8\">

        <div class='jumbotron'>
          <h1>Booktype Themes</h1>
          <p>Change the values below and create PDF. If you like what you see, make sure to save the config file!</p>  
        </div>
  ";
}
/*
* HTML for the end of the page
*/
function HTML_print_end() {
print "
        </div>
      </div><!-- /.row -->
    </div><!-- /.container -->
</body>

</html>
";
}
?>