$(document).ready(function () {
    // Filtering products by category
    $('.category-item').on('click', function (e) {
        e.preventDefault();
        let categoryId = $(this).data('id');
        let sort = $('#sort').val();
        updateProducts(categoryId, sort);
        updateURL(categoryId, sort);
    });

    // Sorting products
    $("#sort").on('change', function () {
        let categoryId = $('.category-item.active').data('id') || null;
        let sort = $(this).val();
        updateProducts(categoryId, sort);
        updateURL(categoryId, sort);
    });

    // Updating the product list with an AJAX request
    function updateProducts(categoryId, sort) {
        $.get('assets/web/fetchProducts.php', {category_id: categoryId, sort: sort}, function (data) {
            $('#product-list').html(data);
            $('.category-item').removeClass('active');
            $('.category-item[data-id="' + categoryId + '"]').addClass('active');
        });
    }

    // Update URL without reloading page
    function updateURL(categoryId, sort) {
        let params = new URLSearchParams(window.location.search);
        if (categoryId) {
            params.set('category', categoryId);
        } else {
            params.delete('category');
        }
        params.set('sort', sort);
        history.pushState(null, '', '?' + params.toString());
    }

    // Loading products when the page loads (if there are GET parameters)
    function loadFromURL() {
        let params = new URLSearchParams(window.location.search);
        let categoryId = params.get('category') || null;
        let sort = params.get('sort') || 'price_asc';
        $('#sort').val(sort);
        updateProducts(categoryId, sort);
    }

    // Opening a modal purchase window
    $(document).on('click', '.buy-btn', function () {
        $('#modal-product-name').text($(this).data('name'));
        $('#modal-product-price').text('Ціна: ' + $(this).data('price') + ' грн.');
        $('#buyModal').modal('show');
    });

    // Loading products based on URL at startup
    loadFromURL();
});
