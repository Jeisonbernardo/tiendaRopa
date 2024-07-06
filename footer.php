<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Consultar los datos de la tabla "tiendaconfig"
$sql = "SELECT * FROM tiendaconfig";
$result = $conn->query($sql);

// Inicializar variables para almacenar los datos de la tabla
$nombre = "";
$facebook = "";
$instagram = "";
$whatsapp = "";
$numero = "";
$email = "";
$direccion = "";
$gmap = "";

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $nombre = $row['nombre'];
  $facebook = $row['facebook'];
  $instagram = $row['instagram'];
  $whatsapp = $row['whatsapp'];
  $numero = $row['numero'];
  $email = $row['email'];
  $direccion = $row['dirección'];
  $gmap = $row['googlemaps'];
}


?>
<footer class="cs-footer pt-sm-5 pt-4 bg-dark">
  <div class="container pt-3">
    <div class="row pb-sm-2">
      <div class="col-6 col-sm-3 col-lg-2 mb-4">
        <h3 class="h6 mb-2 pb-1 text-uppercase text-light">Ayuda</h3>
        <ul class="nav nav-light flex-column">
          <!--<li class="nav-item mb-2">
            <a href="#" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Entrega y devoluciones</a>
          </li>-->
          <li class="nav-item mb-2">
            <a href="preguntas.php" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Preguntas frecuentes</a>
          </li>
          <!--<li class="nav-item mb-2">
            <a href="order-tracking.html" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Seguimiento de pedido</a>
          </li>-->
          <!--<li class="nav-item mb-2">
            <a href="blog.html" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Blog</a>
          </li>-->
          <li class="nav-item mb-2">
            <a href="contacto.php" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Contactos</a>
          </li>
        </ul>
      </div>
      <div class="col-6 col-sm-3 col-lg-2 col-xl-3 mb-4">
        <h3 class="h6 mb-2 pb-1 text-uppercase text-light pl-xl-6">Tienda</h3>
        <ul class="nav nav-light flex-column pl-xl-6">
          <!--<li class="nav-item mb-2">
            <a href="#" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Nuevas llegadas</a>
          </li>-->
          <li class="nav-item mb-2">
            <a href="catalogo.php" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Tendencias</a>
          </li>
          <!--<li class="nav-item mb-2">
            <a href="#" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Ofertas</a>
          </li>-->
          <li class="nav-item mb-2">
            <a href="marcas.php" class="nav-link mr-lg-0 mr-sm-4 p-0 font-weight-normal">Marcas</a>
          </li>
        </ul>
      </div>
      <div class="col-sm-6 col-lg-3 pb-2 pb-lg-0 mb-4">
        <h3 class="h6 mb-2 pb-1 text-uppercase text-light">Contacto</h3>
        <ul class="nav nav-light flex-column pb-3">
          <li class="nav-item text-nowrap mb-2">
            <span class="text-light mr-1">Teléfono:</span>
            <a href="tel:+51 <?php echo $numero; ?>" class="nav-link d-inline-block mr-lg-0 mr-sm-4 p-0 font-weight-normal">+51 <?php echo $numero; ?></a>
          </li>
          <li class="nav-item text-nowrap mb-2">
            <span class="text-light mr-1">Email:</span>
            <a id="textoParaCopiar" onclick="copiarAlPortapapeles(event)" class="nav-link d-inline-block mr-lg-0 mr-sm-4 p-0 font-weight-normal">Tupuntodemoda@gmail.com</a>
          </li>
        </ul>
        <a href="<?php echo $facebook; ?>" target="_blank" class="social-btn sb-solid sb-light mr-2">
          <i class="cxi-facebook"></i>
        </a>
        <!--<a href="<#" target="_blank" class="social-btn sb-solid sb-light mr-2">
          <i class="cxi-instagram"></i>
        </a>-->
        <!--<a href="#" class="social-btn sb-solid sb-light mr-2">
          <i class="cxi-twitter"></i>
        </a>-->
        <!--<a href="#" class="social-btn sb-solid sb-light mr-2">
          <i class="cxi-youtube"></i>
        </a>-->
        <!--<a href="#" class="social-btn sb-solid sb-light">
          <i class="cxi-pinterest"></i>
        </a>-->
      </div>
      <div class="col-lg-4 col-xl-3 mb-4">
        <h3 class="h6 mb-3 pb-1 text-uppercase text-light">UBÍCANOS EN:</h3>
        <li class="nav-item text-nowrap mb-2">
          <span class="text-light mr-1">Dirección:</span>
          <a class="nav-link d-inline-block mr-lg-0 mr-sm-4 p-0 font-weight-normal"><?php echo $direccion; ?></a>
        </li>
        <div class="d-flex flex-wrap flex-sm-nowrap">
          <iframe src="<?php echo $gmap; ?>" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>

    </div>
  </div>
  <div class="border-top border-light">
    <div class="container py-4">
      <div class="font-size-xs text-light">
        <span class="font-size-sm">&copy; </span>
        Todos los derechos reservados.
      </div>
    </div>
  </div>
</footer>


<!-- Back to top button-->
<a class="btn-scroll-top" href="#top" data-scroll data-fixed-element>
  <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2"></span>
  <i class="btn-scroll-top-icon cxi-angle-up"></i>
</a>

<!-- Vendor scripts: js libraries and plugins-->
<script src="assets/js/smooth-scroll.polyfills.min.js"></>
<script src="assets/js/nouislider.min.js"></script>
<script src="assets/vendor/jquery/dist/jquery.slim.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
<script src="assets/vendor/tiny-slider/dist/min/tiny-slider.js"></script>
<script src="admin/assets/js/iziToast.min.js"></script>
<link rel="stylesheet" href="admin/assets/css/iziToast.min.css">

<script>
  function openModal() {
    // Agregar las propiedades CSS al body después de hacer clic en el enlace
    document.body.style.overflow = 'hidden';
    document.body.classList.add('modal-open');

    // Cambiar las propiedades del modal
    var modal = document.getElementById('modal-signin');
    modal.classList.add('show');
    modal.style.display = 'block';
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('role', 'dialog');

    // Crear el elemento del backdrop
    var backdropElement = document.createElement('div');
    backdropElement.className = 'cs-offcanvas-backdrop show';

    // Insertar el elemento del backdrop justo antes del cierre del body
    document.body.insertBefore(backdropElement, document.body.lastElementChild);
  }

  function closeModal() {
    // Remover las propiedades CSS del body
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    document.body.classList.remove('modal-open');

    // Revertir las propiedades del modal
    var modal = document.getElementById('modal-signin');
    modal.classList.remove('show');
    modal.style.display = '';
    modal.removeAttribute('aria-modal');
    modal.removeAttribute('role');

    // Eliminar el elemento del backdrop si existe
    var backdropElement = document.querySelector('.cs-offcanvas-backdrop');
    if (backdropElement) {
      backdropElement.remove();
    }
  }
</script>

<!-- <?php
      if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
      ?>
<script>
  function filtrarPorPrecio() {
    const minValueInput = document.querySelector('.cs-range-slider-value-min');
    const maxValueInput = document.querySelector('.cs-range-slider-value-max');

    const minValue = parseFloat(minValueInput.value);
    const maxValue = parseFloat(maxValueInput.value);

    const productos = document.querySelectorAll('.col[data-producto-id]');
    productos.forEach(producto => {
      const precioSpan = producto.querySelector('.precio-span');
      const precioText = precioSpan.textContent;

      // Obtener el símbolo de moneda
      const monedaSymbol = precioText[0];

      // Obtener el valor numérico del precio
      const precioValue = parseFloat(precioText.replace(monedaSymbol, '').replace(',', ''));

      if (precioValue >= minValue && precioValue <= maxValue) {
        producto.style.display = 'block';
      } else {
        producto.style.display = 'none';
      }
    });
  }

  const rangeSlider = document.querySelector('.cs-range-slider');
  rangeSlider.addEventListener('change', filtrarPorPrecio);

  const minValueInput = document.querySelector('.cs-range-slider-value-min');
  const maxValueInput = document.querySelector('.cs-range-slider-value-max');

  minValueInput.addEventListener('input', filtrarPorPrecio);
  maxValueInput.addEventListener('input', filtrarPorPrecio);

  // Llamada inicial para filtrar productos cuando se carga la página
  filtrarPorPrecio();
</script>
<?php
      }
?>-->

<script>
  //evitar el click derecho
  //window.addEventListener('contextmenu', function(e) {
  //e.preventDefault(); // Prevenir el menú contextual
  //});
</script>


<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Obtener el elemento select y agregar un event listener al cambio de selección
      var sortingSelect = document.getElementById('sorting-top');
      sortingSelect.addEventListener('change', function() {
        // Obtener el valor seleccionado
        var selectedValue = this.value;

        // Construir la nueva URL basada en el valor seleccionado
        var newUrl = selectedValue === 'Por defecto' ?
          'http://localhost/tiendaropa/catalogo.php' :
          'http://localhost/tiendaropa/catalogo.php#' + encodeURIComponent(selectedValue);

        // Cambiar la URL utilizando history.pushState
        history.pushState(null, null, newUrl);
      });
    });
  </script>
<?php
}
?>

<script>
  function validatePassword() {
    const passwordInput = document.getElementById('signup-password');
    const confirmPasswordInput = document.getElementById('signup-confirm-password');
    const passwordAlertContainer = document.getElementById('form-password-alert');

    if (passwordInput.value.length < 8) {
      passwordAlertContainer.style.display = 'block';
      passwordAlertContainer.innerHTML = 'La contraseña debe tener al menos 8 caracteres.';
      return false;
    }

    if (passwordInput.value !== confirmPasswordInput.value) {
      passwordAlertContainer.style.display = 'block';
      passwordAlertContainer.innerHTML = 'Las contraseñas no coinciden. Por favor, verifica las contraseñas e intenta nuevamente.';
      return false;
    }

    return true;
  }
</script>

<script>
  function changeGallery(galleryNumber) {
    var captions = document.querySelectorAll(".caption");
    var items = document.querySelectorAll(".gallery-item");

    captions.forEach(function(caption, index) {
      if (index === galleryNumber - 1) {
        caption.style.display = "block";
      } else {
        caption.style.display = "none";
      }
    });

    items.forEach(function(item, index) {
      if (index === galleryNumber - 1) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });
  }
</script>

<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    // Recuperar el valor de selectedCurrency desde localStorage
    const selectedCurrency = localStorage.getItem("selectedCurrency");

    // Cambiar el símbolo de moneda según la selección
    const precioSpans = document.querySelectorAll(".precio-span");
    precioSpans.forEach(precioSpan => {
      const precio = parseFloat(precioSpan.textContent.replace("S/.", ""));
      if (selectedCurrency === "USD") {
        precioSpan.textContent = '$' + (precio * 0.25).toFixed(2);
      } else {
        precioSpan.textContent = 'S/.' + precio.toFixed(2);
      }
    });
  </script>
<?php
}
?>

<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    $(document).ready(function() {
      $('.collapse').on('show.bs.collapse', function() {
        // Cerrar otros paneles cuando uno se abre
        var $this = $(this);
        var $parent = $this.parent();
        $parent.siblings().find('.collapse.show').collapse('hide');
      });

      // Agregar la clase "collapsed" y cambiar el atributo "aria-expanded" a "false"
      $('[data-toggle="collapse"]').addClass('collapsed').attr('aria-expanded', 'false');
    });
  </script>
<?php
}
?>

<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    //Función para mostrar todos los productos
    function showAllProducts() {
      products.forEach(product => {
        product.style.display = 'block';
      });
    }

    // Función para ordenar los productos según la opción seleccionada
    function sortProducts(sortOption) {
      const productGrid = document.querySelector('.row-cols-1[data-filters-columns]');
      const products = Array.from(productGrid.querySelectorAll('.col'));

      switch (sortOption) {
        case 'Por defecto':
          products.sort((a, b) => {
            const idA = parseInt(a.getAttribute('data-producto-id'), 10);
            const idB = parseInt(b.getAttribute('data-producto-id'), 10);
            return idA - idB;
          });
          break;
        case 'Nuevos':
          products.sort((a, b) => {
            const dateA = new Date(a.dataset.createdAt);
            const dateB = new Date(b.dataset.createdAt);
            return dateB - dateA;
          });
          break;
        case 'Precio bajo - alto':
          products.sort((a, b) => {
            // Implementa la lógica para ordenar por precio de menor a mayor
            const priceA = parseFloat(a.querySelector('.precio-span').textContent.substring(2));
            const priceB = parseFloat(b.querySelector('.precio-span').textContent.substring(2));
            return priceA - priceB;
          });
          break;
        case 'Precio alto - bajo':
          products.sort((a, b) => {
            // Implementa la lógica para ordenar por precio de mayor a menor
            const priceA = parseFloat(a.querySelector('.precio-span').textContent.substring(2));
            const priceB = parseFloat(b.querySelector('.precio-span').textContent.substring(2));
            return priceB - priceA;
          });
          break;
        case 'Popularidad':
          products.sort((a, b) => {
            // Implementa la lógica para ordenar por popularidad
            // Puedes utilizar algún valor de "popularidad" asociado a los productos
          });
          break;
        case 'Ordenar A - Z':
          products.sort((a, b) => {
            // Implementa la lógica para ordenar alfabéticamente de A a Z
            const nameA = a.querySelector('.card-product-title a').textContent.toLowerCase();
            const nameB = b.querySelector('.card-product-title a').textContent.toLowerCase();
            return nameA.localeCompare(nameB);
          });
          break;
        case 'Ordenar Z - A':
          products.sort((a, b) => {
            // Implementa la lógica para ordenar alfabéticamente de Z a A
            const nameA = a.querySelector('.card-product-title a').textContent.toLowerCase();
            const nameB = b.querySelector('.card-product-title a').textContent.toLowerCase();
            return nameB.localeCompare(nameA);
          });
          break;
        default:
          // Opción por defecto
          break;
      }

      // Actualiza la interfaz con los productos ordenados
      for (const product of products) {
        productGrid.appendChild(product);
      }
    }

    // Obtener el fragmento de la URL cuando la página se carga
    const initialFragment = window.location.hash.substring(1);

    // Seleccionar la opción y mostrar los productos según el fragmento
    const sortingSelect = document.getElementById('sorting-top'); // Mover esto aquí

    if (initialFragment) {
      sortingSelect.value = decodeURIComponent(initialFragment);
      sortProducts(initialFragment);
    }

    // Manejador de evento para el cambio de opción en el menú de ordenación
    sortingSelect.addEventListener('change', function(event) {
      const selectedOption = event.target.value;
      sortProducts(selectedOption);
    });
  </script>
<?php
}
?>

<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    // Función para mostrar la cantidad de productos por página
    function showProductsPerPage(productsPerPage) {
      const productGrid = document.querySelector('.row-cols-1[data-filters-columns]');
      const products = Array.from(productGrid.querySelectorAll('.col'));

      // Ocultar todos los productos
      products.forEach(product => {
        product.style.display = 'none';
      });

      // Mostrar la cantidad deseada de productos por página
      for (let i = 0; i < productsPerPage; i++) {
        if (products[i]) {
          products[i].style.display = 'block';
        }
      }
    }

    // Función para mostrar todos los productos
    function showAllProducts() {
      const productGrid = document.querySelector('.row-cols-1[data-filters-columns]');
      const products = Array.from(productGrid.querySelectorAll('.col'));

      products.forEach(product => {
        product.style.display = 'block';
      });
    }

    // Manejador de evento para el cambio de opción en el menú de cantidad por página
    const pagerSelect = document.getElementById('pager-top');
    pagerSelect.addEventListener('change', function(event) {
      const selectedOption = event.target.value;
      if (selectedOption === 'Todos') {
        showAllProducts();
      } else {
        showProductsPerPage(selectedOption);
      }
    });

    // Llamada inicial para mostrar la cantidad de productos por página seleccionada por defecto
    const initialProductsPerPage = pagerSelect.value;
    if (initialProductsPerPage === 'Todos') {
      showAllProducts();
    } else {
      showProductsPerPage(initialProductsPerPage);
    }
  </script>
<?php
}
?>

<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    function performSearch() {
      const searchInput = document.getElementById('searchInput');
      const searchTerm = searchInput.value.toLowerCase();

      const products = document.querySelectorAll('.col[data-producto-id]');
      products.forEach(product => {
        const productName = product.querySelector('.card-product-title').textContent.toLowerCase();
        if (productName.includes(searchTerm)) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    }
  </script>
<?php
}
?>

<script src="assets/js/nouislider.min.js"></script>

<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    // Obtener todos los elementos de checkbox de subcategorías
    const subcategoriaCheckboxes = document.querySelectorAll('.custom-control-input');

    // Obtener todos los productos
    const products = document.querySelectorAll('.col[data-subcategoria]');

    // Manejador de evento para el cambio de opción en el menú de subcategorías
    subcategoriaCheckboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function(event) {
        event.preventDefault(); // Detener el comportamiento predeterminado

        filterProductsBySubcategorias();
        updateHash();
      });
    });

    // Función para filtrar los productos por subcategorías seleccionadas
    function filterProductsBySubcategorias() {
      const selectedSubcategorias = Array.from(subcategoriaCheckboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.id.toLowerCase());

      products.forEach(product => {
        const subcategoria = product.dataset.subcategoria.toLowerCase();
        if (selectedSubcategorias.length === 0 || selectedSubcategorias.includes(subcategoria)) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    }

    // Función para actualizar la parte de la URL después del símbolo #
    function updateHash() {
      const selectedSubcategorias = Array.from(subcategoriaCheckboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.id.toLowerCase());

      const newHash = selectedSubcategorias.length > 0 ? `#${selectedSubcategorias.join(',')}` : '';
      history.replaceState({}, document.title, window.location.pathname + newHash);
    }

    // Deseleccionar todas las subcategorías al cargar la página y actualizar el filtro
    window.addEventListener('load', function() {
      subcategoriaCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
      });
      filterProductsBySubcategorias(); // Mostrar todos los productos al inicio

      // Verificar si hay un hash en la URL y actualizar los checkboxes
      const hash = window.location.hash.substring(1);
      if (hash) {
        const hashValues = hash.split(',');
        hashValues.forEach(value => {
          const checkbox = document.getElementById(value);
          if (checkbox) {
            checkbox.checked = true;
          }
        });
        filterProductsBySubcategorias();
      }
    });
  </script>
<?php
}
?>

<?php
if (basename($_SERVER['PHP_SELF']) === 'catalogo.php') {
?>
  <script>
    // Obtener el enlace de la categoría
    const categoriaLinks = document.querySelectorAll('.categoria-link');

    // Función para actualizar el enlace de la categoría en la URL
    function updateCategoriaURL(categoriaNombre) {
      const currentHash = window.location.hash.substring(1);

      // Si el hash actual es igual al nombre de la categoría, quitar el hash
      if (currentHash === categoriaNombre) {
        history.pushState({}, document.title, window.location.pathname);
      } else {
        const newUrl = window.location.origin + window.location.pathname + '#' + categoriaNombre;
        history.pushState({}, document.title, newUrl);
        expandCategoria(categoriaNombre);
      }
    }

    // Función para expandir la categoría según el nombre de la categoría
    function expandCategoria(categoriaNombre) {
      categoriaLinks.forEach(link => {
        const linkCategoria = link.getAttribute('data-categoria');
        const linkTarget = link.getAttribute('data-target');
        if (linkCategoria === categoriaNombre) {
          const categoriaPanel = document.querySelector(linkTarget);
          if (categoriaPanel) {
            const collapse = new bootstrap.Collapse(categoriaPanel);
            collapse.show();
          }
        }
      });
    }

    // Manejador de evento para el clic en el enlace de la categoría
    categoriaLinks.forEach(link => {
      link.addEventListener('click', function(event) {
        event.preventDefault();
        const categoriaNombre = link.getAttribute('data-categoria');
        updateCategoriaURL(categoriaNombre);
      });
    });

    // Verificar si hay un hash en la URL y expandir la categoría correspondiente
    const hash = window.location.hash.substring(1);
    if (hash) {
      expandCategoria(hash);
    }
  </script>
<?php
}
?>

<!-- Mostrar mensaje de éxito si existe -->
<?php if (isset($success)) : ?>
  <script src="assets/js/iziToast.min.js"></script>
  <link rel="stylesheet" href="assets/css/iziToast.min.css">
  <script>
    window.onload = function() {
      iziToast.success({
        title: "Éxito",
        message: "<?php echo $success; ?>",
        position: "topRight"
      });
    };
  </script>
<?php endif; ?>

<!-- Mostrar mensaje de error si existe -->
<?php if (isset($error)) : ?>
  <script src="assets/js/iziToast.min.js"></script>
  <link rel="stylesheet" href="assets/css/iziToast.min.css">
  <script>
    window.onload = function() {
      iziToast.error({
        title: "Error",
        message: "<?php echo $error; ?>",
        position: "topRight"
      });
    };
  </script>
<?php endif; ?>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const openSigninModalLink = document.getElementById("open-signin-modal");
    const signinModal = document.getElementById("modal-signin");

    openSigninModalLink.addEventListener("click", function(event) {
      event.preventDefault(); // Evita el comportamiento predeterminado del enlace
      $(signinModal).modal("show"); // Abre el modal usando jQuery
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const checkoutButton = document.getElementById('checkout-button');
    checkoutButton.addEventListener('click', function() {
      const storedCart = JSON.parse(localStorage.getItem('cart')) || [];

      if (storedCart.length > 0) {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = 'detalle-compra.php';

        const cartInput = document.createElement('input');
        cartInput.type = 'hidden';
        cartInput.name = 'cart';
        cartInput.value = JSON.stringify(storedCart);

        form.appendChild(cartInput);
        document.body.appendChild(form);
        form.submit();
      } else {
        iziToast.error({
          title: "Error",
          message: "El carrito está vacío!",
          position: "topLeft"
        });
      }
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-cart-item, .delete-product-cart');
    const emptyCartImage = document.getElementById('empty-cart-image');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault();

        const index = parseInt(button.getAttribute('data-product-index'));
        const storedCart = JSON.parse(localStorage.getItem('cart')) || []; // Definir storedCart aquí

        if (!isNaN(index)) {
          const cartItem = document.querySelector('.cart-item[data-product-index="' + index + '"]');
          if (cartItem) {
            cartItem.remove();

            iziToast.show({
              title: '¡Producto eliminado!',
              message: 'El producto ha sido eliminado del carrito.',
              color: 'success',
              position: 'topLeft',
              timeout: 1500,
            });
          }

          if (index >= 0 && index < storedCart.length) {
            storedCart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(storedCart));
            renderCart();
          }
        }

        if (storedCart.length === 0) {
          emptyCartImage.style.display = 'block';
        } else {
          emptyCartImage.style.display = 'none';
        }
      });
    });
  });
</script>


<!-- Main theme script-->
<script src="assets/js/cleave.min.js"></script>
<script src="assets/js/theme.min.js"></script>
<script src="assets/js/simplebar.min.js"></script>
<script src="assets/js/java.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>




</body>

</html>