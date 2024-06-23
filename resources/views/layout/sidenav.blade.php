<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">

        @php
            $Admin = 1;
            $Keuangan = 2;
            $Logistik = 3;
            $Direktur = 4;
            $currentUserRole = Auth::user()->id_role;
            $allowedViewRolesProject = [$Admin, $Keuangan, $Logistik, $Direktur];
            $allowedViewRolesMaster = [$Admin, $Keuangan, $Logistik, $Direktur];
            $allowedViewRolesTransaction = [$Admin, $Keuangan, $Direktur];
            $allowedViewRolesFinance = [$Admin, $Keuangan, $Direktur];
            $allowedViewRolesAccounting = [$Admin, $Keuangan, $Direktur];
            $allowedViewRolesInventory = [$Admin, $Logistik, $Keuangan, $Direktur];
        @endphp
        <!-- Brand -->
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="/">
                <h4 style="color: blue">PT GMJ DASHBOARD</h4>
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="navbar-collapse collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ $sessionRoute == 'dashboard' ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">Dashboards</span>
                        </a>
                    </li>
                    @if (in_array($currentUserRole, $allowedViewRolesProject))
                        <li class="nav-item">
                            @php
                                $projectRoute = [
                                    'admin.projecttype',
                                    'admin.project',
                                    'admin.addProjectView',
                                    'admin.editProjectView',
                                    'admin.projectrecapview',
                                    'admin.projectrealisationview',
                                    'admin.addProjectrealisationview',
                                    'admin.editProjectrealisationview',
                                ];
                            @endphp

                            <a class="nav-link {{ in_array($sessionRoute, $projectRoute) ? 'active' : '' }}"
                                href="#navbar-projects" data-toggle="collapse" role="button"
                                aria-expanded="{{ in_array($sessionRoute, $projectRoute) ? 'true' : '' }}"
                                aria-controls="navbar-projects">
                                <i class="ni ni-settings text-orange"></i>
                                <span class="nav-link-text">Projects</span>
                            </a>
                            <div class="{{ in_array($sessionRoute, $projectRoute) ? 'show' : '' }} collapse"
                                id="navbar-projects">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item {{ $sessionRoute == 'admin.projecttype' ? 'active' : '' }}">
                                        <a href="{{ route('admin.projecttype') }}" class="nav-link">Project Type</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.project' || $sessionRoute == 'admin.addProjectView' || $sessionRoute == 'admin.editProjectView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.project') }}" class="nav-link">Project</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.projectrecapview' ? 'active' : '' }}">
                                        <a href="{{ route('admin.projectrecapview') }}" class="nav-link">Project
                                            Recap</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.projectrealisationview' || $sessionRoute == 'admin.addProjectrealisationview' || $sessionRoute == 'admin.editProjectrealisationview' ? 'active' : '' }}">
                                        <a href="{{ route('admin.projectrealisationview') }}" class="nav-link">Project
                                            Realisation</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if (in_array($currentUserRole, $allowedViewRolesMaster))
                        <li class="nav-item">
                            @php
                                $allmaster = [
                                    'r_supplier.index',
                                    'r_category.index',
                                    'r_item.index',
                                    'r_customer.index',
                                    'r_unit.index',
                                    'admin.users',
                                    'r_role.index',
                                    'r_upah.index',
                                    'admin.coalist',
                                ];
                            @endphp
                            <a class="nav-link" href="#navbar-master" data-toggle="collapse" role="button"
                                aria-expanded="{{ in_array($sessionRoute, $allmaster) ? 'true' : 'false' }}"
                                aria-controls="navbar-examples">
                                <i class="ni ni-ungroup text-orange"></i>
                                <span class="nav-link-text">Master</span>
                            </a>
                            <div class="{{ in_array($sessionRoute, $allmaster) ? 'show' : '' }} collapse"
                                id="navbar-master">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item {{ $sessionRoute == 'r_supplier.index' ? 'active' : '' }}">
                                        <a href="{{ route('r_supplier.index') }}" class="nav-link">Master Supplier</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'r_category.index' ? 'active' : '' }}">
                                        <a href="{{ route('r_category.index') }}" class="nav-link">Master Category
                                            Item</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'r_item.index' ? 'active' : '' }}">
                                        <a href="{{ route('r_item.index') }}" class="nav-link">Master Item</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'r_customer.index' ? 'active' : '' }}">
                                        <a href="{{ route('r_customer.index') }}" class="nav-link">Master Customer</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'r_unit.index' ? 'active' : '' }}">
                                        <a href="{{ route('r_unit.index') }}" class="nav-link">Master Unit</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.users' ? 'active' : '' }}">
                                        <a href="{{ route('admin.users') }}" class="nav-link">Master User</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'r_role.index' ? 'active' : '' }}">
                                        <a href="{{ route('r_role.index') }}" class="nav-link">Master Role</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'r_upah.index' ? 'active' : '' }}">
                                        <a href="{{ route('r_upah.index') }}" class="nav-link">Master Upah</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.coalist' ? 'active' : '' }}">
                                        <a href="{{ route('admin.coalist') }}" class="nav-link">Master COA</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if (in_array($currentUserRole, $allowedViewRolesTransaction))
                        <li class="nav-item">
                            @php
                                $alltransaction = [
                                    'admin.purchase',
                                    'admin.addPurchaseView',
                                    'admin.editPurchaseView',
                                    'admin.invoice',
                                    'admin.addInvoiceView',
                                ];
                            @endphp
                            <a class="nav-link" href="#navbar-transaction" data-toggle="collapse"
                                role="button"aria-expanded="{{ in_array($sessionRoute, $alltransaction) ? 'true' : 'false' }}"
                                aria-controls="navbar-transaction">
                                <i class="ni ni-ui-04 text-info"></i>
                                <span class="nav-link-text">Transactions</span>
                            </a>
                            <div class="{{ in_array($sessionRoute, $alltransaction) ? 'show' : '' }} collapse"
                                id="navbar-transaction">
                                <ul class="nav nav-sm flex-column">
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.purchase' || $sessionRoute == 'admin.addPurchaseView' || $sessionRoute == 'admin.editPurchaseView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.purchase') }}" class="nav-link">Purchase
                                            Transaction</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.invoice' || $sessionRoute == 'admin.addInvoiceView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.invoice') }}" class="nav-link">Invoice</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if (in_array($currentUserRole, $allowedViewRolesFinance))
                        <li class="nav-item">
                            @php
                                $allfinance = [
                                    'admin.payment',
                                    'admin.addPaymentView',
                                    'admin.editPaymentView',
                                    'admin.cashbook',
                                    'admin.addCashbookView',
                                    'admin.advancedreceipt',
                                    'admin.addAdvancedReceiptView',
                                    'admin.editAdvancedReceiptView',
                                    'admin.receipt',
                                    'admin.addReceiptView',
                                    'admin.editReceiptView',
                                ];
                            @endphp
                            <a class="nav-link" href="#navbar-finance" data-toggle="collapse" role="button"
                                aria-expanded="{{ in_array($sessionRoute, $allfinance) ? 'true' : 'false' }}"
                                aria-controls="navbar-finance">
                                <i class="ni ni-money-coins text-info"></i>
                                <span class="nav-link-text">Finance</span>
                            </a>
                            <div class="{{ in_array($sessionRoute, $allfinance) ? 'show' : '' }} collapse"
                                id="navbar-finance">
                                <ul class="nav nav-sm flex-column">
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.payment' || $sessionRoute == 'admin.addPaymentView' || $sessionRoute == 'admin.editPaymentView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.payment') }}" class="nav-link">Payment</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.receipt' || $sessionRoute == 'admin.addReceiptView' || $sessionRoute == 'admin.editReceiptView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.receipt') }}" class="nav-link">Receipt</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.cashbook' || $sessionRoute == 'admin.addCashbookView' || $sessionRoute == 'admin.editCashbookView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.cashbook') }}" class="nav-link">Cash Book</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.advancedreceipt' || $sessionRoute == 'admin.addAdvancedReceiptView' || $sessionRoute == 'admin.editAdvancedReceiptView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.advancedreceipt') }}" class="nav-link">Advance
                                            Receipt</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if (in_array($currentUserRole, $allowedViewRolesAccounting))
                        <li class="nav-item">
                            @php
                                $allaccounting = [
                                    'admin.journal',
                                    'admin.addJournalView',
                                    'admin.editJournalView',
                                    'admin.ledger',
                                    'admin.trialbalance',
                                    'admin.profitloss',
                                    'admin.balancesheet',
                                    // 'admin.addPaymentView',
                                    // 'admin.editPaymentView',
                                    // 'admin.cashbook',
                                    // 'admin.addCashbookView',
                                    // 'admin.advancedreceipt',
                                    // 'admin.addAdvancedReceiptView',
                                    // 'admin.editAdvancedReceiptView',
                                    // 'admin.receipt',
                                    // 'admin.addReceiptView',
                                    // 'admin.editReceiptView',
                                ];
                            @endphp
                            <a class="nav-link" href="#navbar-forms" data-toggle="collapse" role="button"
                                aria-expanded="{{ in_array($sessionRoute, $allaccounting) ? 'true' : 'false' }}"
                                aria-controls="navbar-forms">
                                <i class="ni ni-single-copy-04 text-pink"></i>
                                <span class="nav-link-text">Accounting</span>
                            </a>
                            <div class="{{ in_array($sessionRoute, $allaccounting) ? 'show' : '' }} collapse"
                                id="navbar-forms">
                                <ul class="nav nav-sm flex-column">
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.journal' || $sessionRoute == 'admin.addJournalView' || $sessionRoute == 'admin.editJournalView' ? 'active' : '' }}">
                                        <a href="{{ route('admin.journal') }}" class="nav-link">Journal</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.ledger' ? 'active' : '' }}">
                                        <a href="{{ route('admin.ledger') }}" class="nav-link">Ledger Report</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.trialbalance' ? 'active' : '' }}">
                                        <a href="{{ route('admin.trialbalance') }}" class="nav-link">Trial Balance
                                            Report</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.profitloss' ? 'active' : '' }}">
                                        <a href="{{ route('admin.profitloss') }}" class="nav-link">Profit & Loss
                                            Report</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.capitalchange' ? 'active' : '' }}">
                                        <a href="{{ route('admin.capitalchange') }}" class="nav-link">Stament Of
                                            Change
                                            In
                                            Capital Report</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.balancesheet' ? 'active' : '' }}">
                                        <a href="{{ route('admin.balancesheet') }}" class="nav-link">Balance Sheet
                                            Report</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if (in_array($currentUserRole, $allowedViewRolesInventory))
                        <li class="nav-item">
                            @php
                                $allinventory = [
                                    'admin.pr',
                                    'admin.addprview',
                                    'admin.editprview',
                                    'admin.iin',
                                    'admin.iout',
                                    'admin.stocks',
                                    'admin.stockreminder',
                                    'admin.stockcard',
                                ];
                            @endphp
                            <a class="nav-link" href="#navbar-tables" data-toggle="collapse" role="button"
                                aria-expanded="{{ in_array($sessionRoute, $allinventory) ? 'true' : 'false' }}"
                                aria-controls="navbar-tables">
                                <i class="ni ni-box-2 text-default"></i>
                                <span class="nav-link-text">Inventory</span>
                            </a>
                            <div class="{{ in_array($sessionRoute, $allinventory) ? 'show' : '' }} collapse"
                                id="navbar-tables">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item {{ $sessionRoute == 'admin.iin' ? 'active' : '' }}">
                                        <a href="{{ route('admin.iin') }}" class="nav-link">Inventory In</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.iout' ? 'active' : '' }}">
                                        <a href="{{ route('admin.iout') }}" class="nav-link">Inventory Out</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.stocks' ? 'active' : '' }}">
                                        <a href="{{ route('admin.stocks') }}" class="nav-link">Stocks</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.stockreminder' ? 'active' : '' }}">
                                        <a href="{{ route('admin.stockreminder') }}" class="nav-link">Stock
                                            Reminder</a>
                                    </li>
                                    <li class="nav-item {{ $sessionRoute == 'admin.stockcard' ? 'active' : '' }}">
                                        <a href="{{ route('admin.stockcard') }}" class="nav-link">Stock Card</a>
                                    </li>
                                    <li
                                        class="nav-item {{ $sessionRoute == 'admin.pr' || $sessionRoute == 'admin.addprview' || $sessionRoute == 'admin.editprview' ? 'active' : '' }}">
                                        <a href="{{ route('admin.pr') }}" class="nav-link">Purchase Request</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
                <!-- Divider -->
                <hr class="my-3" />
            </div>
        </div>
</nav>
