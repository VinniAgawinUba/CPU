<div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="view-register.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Registered Users
                            </a>

                            <!--- Inventory -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInventory" aria-expanded="false" aria-controls="collapseInventory">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes-stacked"></i></div>
                                Inventory
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseInventory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="inventory-add.php">Add Inventory</a>
                                    <a class="nav-link" href="inventory-view.php">View Inventory</a>
                                </nav>
                            </div>

                            <!--- Requests -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRequests" aria-expanded="false" aria-controls="collapseRequests">
                                <div class="sb-nav-link-icon"><i class="fas fa-sheet-plastic"></i></div>
                                Requests
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseRequests" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="request-add.php">Add Requests</a>
                                    <a class="nav-link" href="request-view.php">View Requests</a>
                                </nav>
                            </div>


                            <!--- Faculty -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFaculty" aria-expanded="false" aria-controls="collapseFaculty">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Faculty
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseFaculty" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="faculty-add.php">Add Faculty</a>
                                    <a class="nav-link" href="faculty-view.php">View Faculty</a>
                                </nav>
                            </div>

                            


                            <div class="sb-sidenav-menu-heading">Interface</div>
                            

                            

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseColleges" aria-expanded="false" aria-controls="collapseColleges">
                                <div class="sb-nav-link-icon"><i class="fas fa-school"></i></div>
                                School
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseColleges" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseColleges" aria-expanded="false" aria-controls="pagesCollapseColleges">
                                        Colleges
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseColleges" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="college-add.php">Add Colleges</a>
                                            <a class="nav-link" href="college-view.php">View Colleges</a>
                                        </nav>
                                    </div>

                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseDepartments" aria-expanded="false" aria-controls="pagesCollapseDepartments">
                                        Departments
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseDepartments" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="department-add.php">Add Departments</a>
                                            <a class="nav-link" href="department-view.php">View Departments</a>
                                        </nav>
                                    </div>

                                    <a class="nav-link" href="school_year-view.php">
                                        <div class="sb-nav-link-icon"><i class="fas fa-calendar"></i></div>
                                        School Years
                                    </a>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            
                            
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <?php if(isset($_SESSION['auth_user'])) : ?>
                        <div class="small">Logged in as:  <a class="small" style="color:white;letter-spacing:3px" aria-expanded="false">
                            <?= $_SESSION['auth_user']['user_name']; ?>
                        </a></div>
                        

                       

                        <a class="small" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i>Return to Front Page</div>
                           
                        </a>

                        
                        <?php endif; ?>
                        
                    </div>
                    
                </nav>
            </div>