<?php
session_start();
include '../conn.php';
if ($_SESSION['user_id'] == '') {
    header('location:../index.php');
}
date_default_timezone_set('Asia/Manila');

$appointmentId = $_GET['appointmentId'];
$result_summary = mysqli_query($connection, "SELECT summary_products, summary_qty, summary_order_id FROM summary_orders WHERE user_summary_id = '$appointmentId'");
?>
<thead>
    <tr>
        <th class="text-center">Item</th>
        <th class="text-center">Quantity</th>
    </tr>
</thead>
<tbody>
<?php
$productQuantity = array(); // Initialize the product quantity array
if (mysqli_num_rows($result_summary) > 0) {
    while ($fetch_cart = mysqli_fetch_assoc($result_summary)) {
        $product = $fetch_cart['summary_products'];
        $quantity = $fetch_cart['summary_qty'];
?>
    <tr>
        <td class="text-center"><?php echo $fetch_cart['summary_products'];?></td>
        <td>
        <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-primary-red quantity-btn" onclick="decrementQuantity(<?php echo $fetch_cart['summary_order_id']; ?>)"><i class="bi bi-dash-lg"></i></button>
                    <input type="hidden" name="update_quantity_id"  value="<?php echo $fetch_cart['summary_order_id']; ?>" >
                    <input type="number" name="update_quantity" id="update_quantity_<?php echo $fetch_cart['summary_order_id']; ?>" min="0" max="12" class="btn bg-dark text-white" value="<?php echo $fetch_cart['summary_qty']; ?>" onchange="updateDatabase(this)" disabled>
                    <button type="button" class="btn btn-primary-red quantity-btn" onclick="incrementQuantity(<?php echo $fetch_cart['summary_order_id']; ?>)"><i class="bi bi-plus-lg"></i></button>
                </div>
        </td>
    </tr>
<?php
    }
} else {
?>
    <tr>
        <td colspan="2" class="text-center text-black">No available orders</td>
    </tr>
<?php
}
?>
</tbody>
<script>
    function updateDatabase(inputField) {
        const updateValue = inputField.value;
        const updateId = inputField.parentElement.querySelector('[name="update_quantity_id"]').value;

        // Update the database using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'activate-table-edit.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(`update_id=${updateId}&update_value=${updateValue}`);  // Fix the variable name here
    }

    function incrementQuantity(cartId) {
        const quantityInput = document.getElementById('update_quantity_' + cartId);
        quantityInput.stepUp();
        updateDatabase(quantityInput);
    }

    function decrementQuantity(cartId) {
        const quantityInput = document.getElementById('update_quantity_' + cartId);
        quantityInput.stepDown();
        updateDatabase(quantityInput);
    }
</script>