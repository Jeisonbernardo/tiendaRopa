


document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    const cartBody = document.querySelector('.cs-offcanvas-body');
    const cartCountElement = document.querySelector('.cart-count');

    const storedCart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalAmount = 0;

    function updateTotal() {
        const totalElement = document.getElementById('total-amount');
        if (storedCart.length === 0) {
        totalElement.textContent = 'S/. 0.00 ($0.00)';
        } else {
        totalElement.textContent = `S/. ${totalAmount.toFixed(2)} ($${(totalAmount / 4.0).toFixed(2)})`;
        }
    }

    function renderCart() {
        cartBody.innerHTML = '';
        if (cartCountElement !== null) {
        cartCountElement.textContent = storedCart.length;
        }

        const cartTitleElement = document.getElementById('cart-title');
        const cartItemElement = document.getElementById('cart-item');

        cartTitleElement.innerHTML = `Tú carrito (${storedCart.length})`;
        cartItemElement.innerHTML = `${storedCart.length}`;

        totalAmount = 0;

        storedCart.forEach((producto, index) => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('media', 'p-4', 'border-bottom', 'mx-n4');
            const cartItemId = `cart-item-${index}`;

            const precioTotal = parseFloat(producto.precio) * producto.cantidad;

            cartItem.innerHTML = `
            <a href="<?php echo VIEWSTIENDA_PATH; ?>detalle-producto.php?id=${producto.id}" style="min-width: 80px;">
            <img src="${producto.imagen}" width="80" alt="Product thumb">
            </a>
            <div class="media-body pl-3">
                <div class="d-flex justify-content-between">
                    <div class="pr-2">
                        <h3 class="font-size-sm mb-3">
                        <a href="<?php echo VIEWSTIENDA_PATH; ?>detalle-producto.php?id=${producto.id}" class="nav-link font-weight-bold">${producto.nombre}</a>
                        </h3>
                    
                        <ul class="list-unstyled font-size-xs mt-n2 mb-2">
                            <li class="mb-0"><span class="text-muted">Categoria:</span>${producto.categoria}</li>
                            <li class="mb-0"><span class="text-muted">Marca:</span>${producto.marca}</li>
                        </ul>
                        <div class="d-flex align-items-center">
                        
                            <input type="number" class="form-control form-control-sm bg-light mr-3 cart-quantity-input" style="width: 4.5rem;" value="${producto.cantidad}" required min="0" max="${producto.stock}" data-cart-item="${cartItemId}">
                            <span class="h5 d-inline-block mb-0" data-cart-total="${cartItemId}">
                                S/. ${precioTotal.toFixed(2)} ($${(precioTotal / 4.0).toFixed(2)})
                            </span>
                        </div>
                        
                        <button class="btn btn-link btn-sm text-decoration-none px-0 pb-0" data-product-index="${index}">
                            Agregar a
                            <i class="cxi-heart ml-1"></i>
                        </button>
                    </div>

                    <div class="nav-muted mr-n2">
                        <a href="#" class="btn btn-link btn-sm text-decoration-none px-0 pb-0 delete-product" data-product-index="${index}">
                            <i class="cxi-delete ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>`;

            cartBody.appendChild(cartItem);

            if (storedCart.length > 0) {
                totalAmount += parseFloat(producto.precioTotal);
            }

            const cantidadInput = cartItem.querySelector(`[data-cart-item="${cartItemId}"]`);
            cantidadInput.addEventListener('input', function(event) {
                const newCantidad = parseInt(event.target.value, 10);
                if (!isNaN(newCantidad) && newCantidad >= 0) {
                    producto.cantidad = newCantidad;
                    producto.precioTotal = parseFloat(producto.precio) * newCantidad;

                    localStorage.setItem('cart', JSON.stringify(storedCart));

                    const precioTotalElement = cartItem.querySelector(`[data-cart-total="${cartItemId}"]`);
                    precioTotalElement.textContent = `S/. ${producto.precioTotal.toFixed(2)} ($${(producto.precioTotal / 4.0).toFixed(2)})`;

                    totalAmount = storedCart.reduce((total, prod) => total + prod.precioTotal, 0);
                    updateTotal();
                }
            });

            const deleteButton = cartItem.querySelector('.delete-product');
            deleteButton.addEventListener('click', function(event) {
                event.preventDefault();
                storedCart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(storedCart));
                renderCart();

                iziToast.show({
                    title: '¡Producto eliminado!',
                    message: 'El producto ha sido eliminado del carrito.',
                    color: 'success',
                    position: 'topLeft',
                    timeout: 1500,
                });
            });


            const deleteCartButtons = document.querySelectorAll('.delete-cart-item');
            deleteCartButtons.forEach(deleteButton => {
                deleteButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    const productIndex = parseInt(deleteButton.getAttribute('data-product-index'));
                    storedCart.splice(productIndex, 1);
                    localStorage.setItem('cart', JSON.stringify(storedCart));
                    renderCart();

                    iziToast.show({
                        title: '¡Producto eliminado!',
                        message: 'El producto ha sido eliminado del carrito.',
                        color: 'success',
                        position: 'topLeft',
                        timeout: 1500,
                    });
                });
            });
        });

        updateTotal();
    }

    function updateTotal() {
        const totalElement = document.getElementById('total-amount');
        if (storedCart.length === 0) {
            totalElement.textContent = 'S/. 0.00';
        } else {
            totalElement.textContent = `S/. ${totalAmount.toFixed(2)} ($${(totalAmount / 4.0).toFixed(2)})`;
        }
    }

    // Mostrar los productos en el carrito al cargar la página
    renderCart();

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const nombre = button.getAttribute('data-product-nombre');
            const precio = button.getAttribute('data-product-precio');
            const imagen = button.getAttribute('data-product-imagen');
            const marca = button.getAttribute('data-product-marca');
            const categoria = button.getAttribute('data-product-categoria');
            const id = button.getAttribute('data-product-id');
            const stock = parseInt(button.getAttribute('data-product-stock'));

            // Verificar si el producto ya está en el carrito
            const productoExistente = storedCart.find(item => item.nombre === nombre);
            if (productoExistente) {
                if (productoExistente.cantidad >= stock) { // Verificar si se alcanzó el límite de stock
                    iziToast.error({
                        title: 'Error',
                        timeout: 1500,
                        message: 'Se alcanzó el límite de stock para este producto',
                        position: 'topLeft'
                    });
                    return;
                }
                // Aumentar la cantidad y actualizar el precio
                productoExistente.cantidad += 1;
                productoExistente.precioTotal = parseFloat(productoExistente.precio) * productoExistente.cantidad;

                localStorage.setItem('cart', JSON.stringify(storedCart));

                iziToast.success({
                    title: 'Éxito',
                    timeout: 1500,
                    message: `Se aumentó la cantidad de ${nombre} en el carrito`,
                    position: 'topLeft'
                });

                // Actualizar la visualización del carrito
                renderCart();
                return;
            }

            storedCart.push({
                nombre: nombre,
                precio: precio,
                imagen: imagen,
                marca: marca,
                categoria: categoria,
                cantidad: 1, // Agregar la cantidad inicial
                precioTotal: parseFloat(precio), // Agregar el precio total inicial
                stock: stock,
                id: id
            });

            localStorage.setItem('cart', JSON.stringify(storedCart));

            iziToast.success({
                title: 'Éxito',
                timeout: 1500,
                message: "Producto añadido al carrito: " + nombre,
                position: 'topLeft'
            });

            // Actualizar la visualización del carrito
            renderCart();
        });
    });


    // Después de definir cartCountElement
    const cartTitleElement = document.getElementById('cart-title');
    const cartItemElement = document.getElementById('cart-item');
});
