function toggleSorticon() {
    jQuery(".th-clickable").each((_, el) => {
        jQuery(el).on('click', () => {
            jQuery(el).attr('aria-sort', jQuery(el).attr('aria-sort') == 'ascending' ? 'descending' : 'ascending')
        })
    })
}
toggleSorticon();
