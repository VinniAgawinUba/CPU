<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include ('authentication.php');
//Add Department
if(isset($_POST['add_department']))
{
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];

    //Insert the Department
    $query = "INSERT INTO department (name, college_id) VALUES ('$name', '$college_id')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New Department has been added";
        header('Location: department-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: department-add.php');
        exit(0);
    }
}

//Update Department
if(isset($_POST['update_department']))
{
    $department_id = $_POST['id'];
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];

    //UPDATE the Department
    $query = "UPDATE department SET name = '$name', college_id = '$college_id' WHERE id = '$department_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Department has been Updated";
        header('Location: department-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: department-edit.php');
        exit(0);
    }
}


//Add College
if(isset($_POST['add_college']))
{
    $name = $_POST['name'];

    //Insert the College
    $query = "INSERT INTO college (name) VALUES ('$name')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New College has been added";
        header('Location: college-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: college-add.php');
        exit(0);
    }
}

//Update College
if(isset($_POST['update_college']))
{
    $college_id = $_POST['id'];
    $name = $_POST['name'];

    //UPDATE the College
    $query = "UPDATE college SET name = '$name' WHERE id = '$college_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "College has been Updated";
        header('Location: college-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: college-edit.php');
        exit(0);
    }
}

//Add Partner
if(isset($_POST['partner_add_btn']))
{
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact_person = $_POST['contact_person'];
    $designation = $_POST['designation'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    //Insert the Partner
    $query = "INSERT INTO partners (name, address, contact_person, designation, email, contact_number) VALUES ('$name', '$address', '$contact_person', '$designation', '$email', '$contact_number')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New Partner has been added";
        header('Location: partner-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: partner-add.php');
        exit(0);
    }
}

//Update Partner
if(isset($_POST['update_partner']))
{
    $partner_id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact_person = $_POST['contact_person'];
    $designation = $_POST['designation'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    //UPDATE the Partner
    $query = "UPDATE partners SET name = '$name', address = '$address', contact_person = '$contact_person', designation = '$designation', email = '$email', contact_number = '$contact_number' WHERE id = '$partner_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Partner has been Updated";
        header('Location: partner-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: partner-edit.php');
        exit(0);
    }
}


//Add Faculty
if(isset($_POST['add_faculty'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $full_name = $fname.''.$lname;
    $email = $_POST['email'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $role = $_POST['role'];

    // Image Upload
    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' .$image_extension;


    // Insert the Post with the category_id
    $query = "INSERT INTO faculty (fname, lname, full_name, email, college_id, department_id, role, image) 
              VALUES ('$fname', '$lname', '$full_name', '$email', '$college_id', '$department_id', '$role', '$filename')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        // Upload the image to uploads folder
        move_uploaded_file($_FILES['image']['tmp_name'],'../uploads/faculty/'.$filename);
        $_SESSION['message'] = "New Faculty has been added";
        header('Location: faculty-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: faculty-add.php');
        exit(0);
    }
}

//Update Faculty
if(isset($_POST['update_faculty'])) {
    $faculty_id = $_POST['faculty_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $full_name = $fname.''.$lname;
    $email = $_POST['email'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $role = $_POST['role'];

    // Image Upload
    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' .$image_extension;

    $old_filename = $_POST['old_image'];

    // Update the Faculty with the new values
    $query = "UPDATE faculty SET fname = '$fname', lname = '$lname', full_name = '$full_name', email = '$email', college_id = '$college_id', department_id = '$department_id', role = '$role', image = '$filename' WHERE id = '$faculty_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        if($image != NULL) {
            if(file_exists('../uploads/faculty/' . $_POST['old_image'])) 
            {
                unlink('../uploads/faculty/' . $_POST['old_image']);
            }
            // Upload the image to uploads folder
            move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/faculty/' . $filename);
        }
        $_SESSION['message'] = "Faculty has been updated";
        header('Location: faculty-edit.php?id='.$faculty_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: faculty-edit.php?id='.$faculty_id);
        exit(0);
    }
}


//Add Student
if(isset($_POST['add_student']))
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $year_level = $_POST['year_level'];
    $student_number = $_POST['student_number'];
    $project_id = $_POST['project_id'];

    //Insert the Student
    $query = "INSERT INTO students (fname, lname, college_id, department_id, year_level, student_number, project_id) VALUES ('$fname', '$lname', '$college', '$department', '$year_level', '$student_number', '$project_id')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New Student has been added";
        header('Location: student-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: student-add.php');
        exit(0);
    }
}

//Update Student
if(isset($_POST['update_student']))
{
    $student_id = $_POST['student_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $year_level = $_POST['year_level'];
    $student_number = $_POST['student_number'];
    $project_id = $_POST['project_id'];

    //UPDATE the Student
    $query = "UPDATE students SET fname = '$fname', lname = '$lname', college_id = '$college_id', department_id = '$department_id', year_level = '$year_level', student_number = '$student_number', project_id = '$project_id' WHERE id = '$student_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Student has been Updated";
        header('Location: student-edit.php?id='.$student_id);
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: student-edit.php?id='.$student_id);
        exit(0);
    }
}



// Add Project
if(isset($_POST['project_add_btn'])) {
    $name = $_POST['pname'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $subject_hosted = $_POST['subject_hosted'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $sd_coordinator_id = $_POST['sd_coordinator_id'];
    $partner_id = $_POST['partner_id'];
    $school_year_id = $_POST['school_year_id'];
    $semester = $_POST['semester'];
    $status = $_POST['status']; // Assuming all projects start as "In Progress"

    // Insert the Project into the database
    $query = "INSERT INTO projects (name, type, description, subject_hosted, college_id, department_id, sd_coordinator_id, partner_id, school_year_id, semester, status) 
              VALUES ('$name', '$type', '$description', '$subject_hosted', '$college_id', '$department_id', '$sd_coordinator_id', '$partner_id', '$school_year_id', '$semester', '$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $project_id = mysqli_insert_id($con); // Get the last inserted project_id

        // Debugging statement
        echo "<pre>";
        print_r($_FILES['project_documents']);
        echo "</pre>";

        // Upload multiple files
        if(isset($_FILES['project_documents'])) {
            $file_count = count($_FILES['project_documents']['name']);
            for($i = 0; $i < $file_count; $i++) {
                $file_name = $_FILES['project_documents']['name'][$i];
                $file_tmp = $_FILES['project_documents']['tmp_name'][$i];
                $file_type = $_FILES['project_documents']['type'][$i];
                $file_size = $_FILES['project_documents']['size'][$i];
                $file_error = $_FILES['project_documents']['error'][$i];

                if($file_error === UPLOAD_ERR_OK) {
                    $file_destination = '../uploads/project_documents/' . $file_name;
                    if(move_uploaded_file($file_tmp, $file_destination)) {
                        // Insert file details into project_documents table
                        $query = "INSERT INTO project_documents (project_id, file_name, file_type, file_size, file_path) 
                                  VALUES ('$project_id', '$file_name', '$file_type', '$file_size', '$file_destination')";
                        $query_run = mysqli_query($con, $query);

                        if(!$query_run) {
                            $_SESSION['message'] = "Error inserting file details into database";
                            header('Location: project-add.php');
                            exit(0);
                        }
                    } else {
                        $_SESSION['message'] = "Error moving uploaded file to destination folder";
                        header('Location: project-add.php');
                        exit(0);
                    }
                } else {
                    $_SESSION['message'] = "Error uploading file: " . $_FILES['project_documents']['name'][$i];
                    header('Location: project-add.php');
                    exit(0);
                }
            }
        } else {
            // Debugging statement
            echo "No project_documents uploaded";
        }

        $_SESSION['message'] = "New Project has been added";
        header('Location: project-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: project-add.php');
        exit(0);
    }
}


// Update Project
if (isset($_POST['project_edit_btn'])) {
    $project_id = $_POST['project_id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $subject_hosted = $_POST['subject_hosted'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $sd_coordinator_id = $_POST['sd_coordinator_id'];
    $partner_id = $_POST['partner_id'];
    $school_year_id = $_POST['school_year_id'];
    $semester = $_POST['semester'];
    $status = $_POST['status'];

    // Begin by updating project details
    $query = "UPDATE projects SET name = '$name', type = '$type', description = '$description', subject_hosted = '$subject_hosted', college_id = '$college_id', department_id = '$department_id', sd_coordinator_id = '$sd_coordinator_id', partner_id = '$partner_id', school_year_id = '$school_year_id', semester = '$semester', status = '$status' WHERE id = '$project_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        // Check if any files are uploaded
        if (!empty($_FILES['project_documents']['name'][0])) {
            $file_count = count($_FILES['project_documents']['name']);
            for ($i = 0; $i < $file_count; $i++) {
                $file_name = $_FILES['project_documents']['name'][$i];
                $file_tmp = $_FILES['project_documents']['tmp_name'][$i];
                $file_type = $_FILES['project_documents']['type'][$i];
                $file_size = $_FILES['project_documents']['size'][$i];
                $file_error = $_FILES['project_documents']['error'][$i];

                if ($file_error === UPLOAD_ERR_OK) {
                    $file_destination = '../uploads/project_documents/' . $file_name;
                    move_uploaded_file($file_tmp, $file_destination);

                    // Insert file details into project_documents table
                    $query = "INSERT INTO project_documents (project_id, file_name, file_type, file_size, file_path) 
                              VALUES ('$project_id', '$file_name', '$file_type', '$file_size', '$file_destination')";
                    $query_run = mysqli_query($con, $query);
                }
            }
        }

        $_SESSION['message'] = "Project has been updated";
        header('Location: project-edit.php?id=' . $project_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: project-edit.php?id=' . $project_id);
        exit(0);
    }
}

//Delete Project Document
if(isset($_POST['delete_project_document'])) 
{
    $document_id = $_POST['document_id'];
    $project_id = $_POST['project_id'];

    $query = "DELETE FROM project_documents WHERE id = '$document_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Project Document has been Deleted";
        header('Location: project_details.php?id='.$project_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: project_details.php?id='.$project_id);
        exit(0);
    }
}


//Delete Post
if(isset($_POST['post_delete_btn'])) 
{
    $post_id = $_POST['id'];

    $check_img_query = "SELECT * FROM posts WHERE id = '$post_id' LIMIT 1";
    $img_res = mysqli_query($con, $check_img_query);
    $res_data = mysqli_fetch_all($img_res);

    $image = $res_data['image'];
   
    $query = "DELETE FROM posts WHERE id = '$post_id' LIMIT 1";
    $query_run = mysqli_query($con, $query);

    

    if($query_run) {
            if(file_exists('../uploads/posts/'.$image)) 
            {
                unlink('../uploads/posts/'.$image);
            }

        $_SESSION['message'] = "Post has been Deleted";
        header('Location: post-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: post-view.php');
        exit(0);
    }

}


//Update Post
if(isset($_POST['post_update'])) {
    $post_id = $_POST['post_id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $old_filename = $_POST['old_image'];
    // Image Upload
    $image = $_FILES['image']['name'];

    $filename = '';
    if($image != NULL) {
        // Rename this image
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time() . '.' . $image_extension;
    } 
    else 
    {
        $filename = $old_filename;
    }

    $status = $_POST['status'] == true ? '1' : '0';

    // Update the Post with the category_id
    $query = "UPDATE posts SET category_id = '$category_id', name = '$name', slug = '$slug', description = '$description', meta_title = '$meta_title', meta_description = '$meta_description', meta_keyword = '$meta_keyword', image = '$filename', status = '$status' WHERE id = '$post_id'";
    $query_run = mysqli_query($con, $query);
    
    if($query_run) {
        if($image != NULL) {
            if(file_exists('../uploads/posts/' . $_POST['old_image'])) 
            {
                unlink('../uploads/posts/' . $_POST['old_image']);
            }
            // Upload the image to uploads folder
            move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/posts/' . $filename);
        }
        $_SESSION['message'] = "Post has been updated";
        header('Location: post-edit.php?id='.$post_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: post-edit.php?id='.$post_id);
        exit(0);
    }
}



//Add Post
if(isset($_POST['post_add_btn'])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    // Image Upload
    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_extension;

    $status = $_POST['status'] == true ? '1' : '0';

    // Insert the Post with the category_id
    $query = "INSERT INTO posts (category_id, name, slug, description, meta_title, meta_description, meta_keyword, image, status) 
              VALUES ('$category_id', '$name', '$slug', '$description', '$meta_title', '$meta_description', '$meta_keyword', '$filename', '$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        // Upload the image to uploads folder
        move_uploaded_file($_FILES['image']['tmp_name'],'../uploads/posts/'.$filename);
        $_SESSION['message'] = "New Post has been added";
        header('Location: post-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: post-add.php');
        exit(0);
    }
}



//Delete the user
if(isset($_POST['category_delete']))
{
    $category_id = $_POST['category_delete'];

    $query = "DELETE FROM categories WHERE id = '$category_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Category has been deleted";
        header('Location: category-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: category-view.php');
        exit(0);
    }
}

//UPDATE Category
if(isset($_POST['category_edit']))
{
    $category_id = $_POST['id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];

    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $navbar_status = $_POST['navbar_status'] == true ? '1' : '0';
    $status = $_POST['status'] == true ? '1' : '0';

    //UPDATE the Category
    $query = "UPDATE categories SET name = '$name', slug = '$slug', description = '$description', meta_title = '$meta_title', meta_description = '$meta_description',meta_keyword = '$meta_keyword',navbar_status = '$navbar_status', status = '$status' WHERE id = '$category_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Category has been Updated";
        header('Location: category-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: category-edit.php');
        exit(0);
    }
}

//Add Category
if(isset($_POST['category_add']))
{
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];

    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $navbar_status = $_POST['navbar_status'] == true ? '1' : '0';
    $status = $_POST['status'] == true ? '1' : '0';

    //Insert the Category
    $query = "INSERT INTO categories (name, slug, description, meta_title, meta_description, meta_keyword, navbar_status, status) VALUES ('$name', '$slug', '$description', '$meta_title', '$meta_description','$meta_keyword','$navbar_status','$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New Category has been added";
        header('Location: category-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: category-add.php');
        exit(0);
    }
}




//Delete the user
if(isset($_POST['user_delete']))
{
    $user_id = $_POST['user_delete'];

    $query = "DELETE FROM users WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "User has been deleted";
        header('Location: view-register.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

//Add the user
if(isset($_POST['add_user']))
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];
    $status = $_POST['status'] == true ? '1' : '0';

    //Insert the user
    $query = "INSERT INTO users (fname, lname, email, password, role_as, status) VALUES ('$fname', '$lname', '$email', '$password', '$role_as', '$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New User has been added";
        header('Location: view-register.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

//Update the user
if(isset($_POST['update_user']))
{
    $user_id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];
    $status = $_POST['status'] == true ? '1' : '0';

    //Update the user
    $query = "UPDATE users SET fname = '$fname', lname = '$lname', email = '$email', password = '$password', role_as = '$role_as', status = '$status' WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "User has been updated";
        header('Location: view-register.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
    
}

//Add School Year
if(isset($_POST['add_school_year']))
{
    $school_year = $_POST['school_year'];

    //Insert the School Year
    $query = "INSERT INTO school_year (school_year) VALUES ('$school_year')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New School Year has been added";
        header('Location: school_year-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: school_year-add.php');
        exit(0);
    }
}

//Update School Year
if(isset($_POST['update_school_year']))
{
    $school_year_id = $_POST['id'];
    $school_year = $_POST['school_year'];

    //UPDATE the School Year
    $query = "UPDATE school_year SET school_year = '$school_year' WHERE id = '$school_year_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "School Year has been Updated";
        header('Location: school_year-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: school_year-edit.php');
        exit(0);
    }
}

//Logout the user
if(isset($_POST['logout_btn'])){
    //session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    unset($_SESSION['auth_role']);

    $_SESSION['message'] = "Logged Out Successfully";
    header('location: ../login.php');
    exit(0);
}
?>