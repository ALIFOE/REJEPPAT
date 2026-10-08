{{--
    Éditeur de texte riche (TinyMCE 7, auto-hébergé, licence GPL) avec des outils proches de Word.
    Paramètres : $selecteur (champ textarea), $televersement (url d'envoi des images insérées), $hauteur.
--}}
<script src="{{ asset('assets/vendors/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: @json($selecteur),
        license_key: 'gpl',
        base_url: @json(asset('assets/vendors/tinymce')),
        suffix: '.min',
        language: 'fr_FR',
        language_url: @json(asset('assets/vendors/tinymce/langs/fr_FR.js')),
        height: {{ $hauteur ?? 640 }},
        promotion: false,
        branding: false,
        browser_spellcheck: true,
        contextmenu: false,

        // Menus et barre d'outils façon traitement de texte
        menubar: 'file edit view insert format table tools help',
        plugins: 'advlist autolink lists link image charmap preview searchreplace visualblocks code fullscreen insertdatetime table wordcount help quickbars nonbreaking anchor',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough subscript superscript | forecolor backcolor removeformat | '
            + 'alignleft aligncenter alignright alignjustify lineheight | bullist numlist outdent indent | '
            + 'link image table blockquote hr charmap | searchreplace wordcount | preview fullscreen code',
        toolbar_mode: 'wrap',
        toolbar_sticky: true,
        quickbars_insert_toolbar: false,
        quickbars_selection_toolbar: 'bold italic underline | forecolor backcolor | blocks | link',

        block_formats: 'Paragraphe=p; Titre 2=h2; Titre 3=h3; Titre 4=h4; Citation=blockquote; Code=pre',
        font_family_formats: 'Albert Sans (site)=Albert Sans,sans-serif; Parkinsans (titres du site)=Parkinsans,sans-serif; '
            + 'Arial=arial,helvetica,sans-serif; Calibri=calibri,sans-serif; Georgia=georgia,serif; '
            + 'Times New Roman=times new roman,times,serif; Verdana=verdana,geneva,sans-serif; Courier New=courier new,courier,monospace',
        font_size_formats: '12px 14px 15px 16px 17px 18px 20px 22px 24px 28px 32px',
        line_height_formats: '1 1.2 1.5 1.65 2',
        color_map: [
            '2E6B3A', 'Vert REJEPPAT', '1F4A2C', 'Vert foncé', '53D58A', 'Vert clair', 'F4CA4E', 'Jaune REJEPPAT',
            '0C2213', 'Noir', '4D6352', 'Texte', 'FFFFFF', 'Blanc', 'C8473B', 'Rouge',
            '3D7FC4', 'Bleu', 'C98B4A', 'Terre', 'E6F2DE', 'Fond vert pâle', 'FDF2CC', 'Fond jaune pâle'
        ],

        // Tableaux et images
        table_default_attributes: {},
        table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
        image_advtab: true,
        image_caption: false,
        image_title: false,
        automatic_uploads: true,
        paste_data_images: true,
        images_file_types: 'jpg,jpeg,png,webp,gif',
        images_upload_handler: function (blobInfo) {
            var donnees = new FormData();
            donnees.append('file', blobInfo.blob(), blobInfo.filename());

            return fetch(@json($televersement), {
                method: 'POST',
                body: donnees,
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': @json(csrf_token()), 'Accept': 'application/json' }
            }).then(function (reponse) {
                return reponse.json().then(function (json) {
                    if (!reponse.ok) {
                        throw new Error((json.errors && json.errors.file && json.errors.file[0]) || 'Envoi de l’image impossible.');
                    }
                    return json.location;
                });
            });
        },

        // Liens et adresses relatives au site (les images restent valables après un changement de domaine)
        link_default_target: '_blank',
        link_assume_external_targets: 'https',
        relative_urls: false,
        remove_script_host: true,

        // Aperçu fidèle au style des articles du site
        content_css: 'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;600;700&family=Parkinsans:wght@500;600;700&display=swap',
        content_style: 'body { font-family: "Albert Sans", sans-serif; font-size: 17px; line-height: 1.65; color: #4d6352; max-width: 820px; margin: 16px auto; padding: 0 16px; }'
            + ' h2, h3, h4 { font-family: Parkinsans, sans-serif; color: #0c2213; line-height: 1.3; }'
            + ' a { color: #2e6b3a; } img { max-width: 100%; height: auto; border-radius: 10px; }'
            + ' blockquote { margin: 1em 0; padding: 12px 20px; border-left: 4px solid #f4ca4e; background: #f1f8ec; }'
            + ' table td, table th { border: 1px solid #d6e8cc; padding: 8px 10px; } table th { background: #e6f2de; color: #0c2213; }'
    });
</script>
