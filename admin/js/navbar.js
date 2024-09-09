
var navbarstring=` <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
<!-- <div class="sidebar-brand-icon rotate-n-15">
    <i class="fas fa-laugh-wink"></i>
</div> -->
<div class="sidebar-brand-text mx-3">RBF Admin</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
<a class="nav-link" href="index.html">
    <i class="fas fa-fw fa-tachometer-alt"></i>
    <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
Interface
</div>

<!-- Nav Item - Pages Collapse Menu -->

<li class="nav-item">
<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
    aria-controls="collapseTwo">
    <i class="fas fa-fw fa-user"></i>
    <span>Account</span>
</a>
<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <!-- <h6 class="collapse-header">Custom Components:</h6> -->
        <a class="collapse-item" href="profile.html">Profile</a>
        <a class="collapse-item" style="cursor: pointer;" data-toggle="modal" data-target="#logoutModal">Log-Out</a>
    </div>
</div>
</li>

<li class="nav-item">
<a class="nav-link collapsed" href="sitedetails.html"
    aria-expanded="true" aria-controls="collapsesitedet">
    <i class="fas fa-fw fa-store"></i>

    <span>Shop Details</span>
</a>

</li>

<li class="nav-item">
<a class="nav-link collapsed" href="order_list.html"
    aria-expanded="true" aria-controls="collapseOrders">
    <i class="fas fa-fw fa-list-alt"></i>

    <span>Orders</span>
</a>

</li>

<li class="nav-item">
<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventory"
    aria-expanded="true" aria-controls="collapseInventory">
    <i class="fas fa-fw fa-table"></i>
    <span>Products</span>
</a>
<div id="collapseInventory" class="collapse" aria-labelledby="headingInventory"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <!-- <a class="collapse-item" href="productlist.html">Product list</a> -->
            <a class="collapse-item dropdown-toggle" role="button" id="addproduct" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Add Product
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="addproduct">
                <a class="dropdown-item" href="addproduct.html">
                    Single product
                </a>
                <a class="dropdown-item" href="addproduct_v.html">
                    Product with variants
                </a>
            </div>

        <a class="collapse-item" href="stocks.html">Products</a>
        <a class="collapse-item" href="categories.html">Categories</a>
    </div>
</div>
</li>


<li class="nav-item">
<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnalytics" aria-expanded="true"
    aria-controls="collapseAnalytics">
    <i class="fas fa-fw fa-chart-line"></i>
    <span>Reports</span>
</a>
<div id="collapseAnalytics" class="collapse" aria-labelledby="headingAnalytics"
    data-parent="#accordionSidebar">
    <div class="bg-white py-2 collapse-inner rounded">
        <!-- <h6 class="collapse-header">Custom Components:</h6> -->
        <a class="collapse-item" href="sales.html">Sales by Date</a>
        <a class="collapse-item" href="product_sales.html">Product Sales</a>
        <a class="collapse-item" href="category_sales.html">Product Category Sales</a>
        <a class="collapse-item" href="shipment.html">Shipments</a>
    </div>
</div>
</li>




<li class="nav-item">
<a class="nav-link collapsed" href="customers.html"
    aria-expanded="true" aria-controls="collapseCustomer">
    <i class="fas fa-fw fa-user"></i>

    <span>Customers</span>
</a>

</li>


<li class="nav-item">
<a class="nav-link" href="transportapps.html" 
    aria-expanded="true" aria-controls="collapseTransport">
    <i class="fas fa-fw fa-truck"></i>

    <span>Delivery Apps</span>
</a>

</li>




<hr class="sidebar-divider">

<div class="text-center d-none d-md-inline">
<button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>
`;
// <?php
// if($priv==10){
// echo'<li class="nav-item">
//     <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAcc"
//         aria-expanded="true" aria-controls="collapseAcc">
//         <i class="fas fa-fw fa-users"></i>
//         <span>Manage Accounts</span>
//     </a>
//     <div id="collapseAcc" class="collapse" aria-labelledby="headingAcc"
//         data-parent="#accordionSidebar">
//         <div class="bg-white py-2 collapse-inner rounded">
//             <a class="collapse-item" href="acclist.html">Admin accounts</a>
//             <a class="collapse-item" href="create.html">Add account</a>
//             <!-- <a class="collapse-item" href="utilities-other.html">Pickups</a> -->
//         </div>
//     </div>
// </li>';
// }

// ?>

var doc = new DOMParser().parseFromString(navbarstring, "text/html");
document.getElementById("accordionSidebar").appendChild(doc.documentElement);