<?php
    session_start();
?>
<nav
    class="flex flex-col sm:flex-row sm:justify-between p-3 bg-white shadow-sm border-b items-center"
>
    <ul class="flex flex-col sm:flex-row items-center">
        <li class="pl-3 pr-6 mb-4 sm:mb-0">
            <a href="index.php" class="flex items-center">
                <img
                    src="./assets/img/logo.svg"
                    alt="logo"
                    class="w-10 sm:w-12 mr-2"
                />
                <span class="text-blue-900 text-xl">Pangaea</span>
            </a>
        </li>
        <li class="mb-4 sm:mb-0">
            <a
                href="index.php"
                class="text-gray-500 px-6 py-3 hover:bg-indigo-100 rounded-md text-sm"
                >Home</a
            >
        </li>
        <li class="mb-4 sm:mb-0">
            <a
                href="write.php"
                class="text-gray-500 px-6 py-3 hover:bg-indigo-100 rounded-md text-sm"
                >Write</a
            >
        </li>
        <li class="mb-4 sm:mb-0">
            <a
                href=""
                class="text-gray-500 px-6 py-3 hover:bg-indigo-100 rounded-md text-sm"
                >Earnings</a
            >
        </li>
        <li class="mb-4 sm:mb-0">
            <a
                href=""
                class="text-gray-500 px-6 py-3 hover:bg-indigo-100 rounded-md text-sm"
                >Subscription</a
            >
        </li>
    </ul>

    <ul class="hidden sm:flex sm:flex-row sm:items-center">

        <li>
            <div id="searchBox" class="mx-6"></div>
        </li>

        <?php
            if(isset($_SESSION['userId'])) {
        ?>

        <li id="user-drop-down-li">
            <div id="user-drop-down" class="mx-6"></div>
        </li>
        <?php
            }else{
        ?>
        <li class="mb-4 sm:mb-0" id="login-btn">
            <a
                href="login.php"
                class="text-gray-500 px-6 py-3 hover:bg-indigo-100 rounded-md text-sm"
                >Login</a
            >
        </li>
        <li class="mb-4 sm:mb-0" id="register-btn">
            <a
                href="register.php"
                class="text-gray-500 px-6 py-3 hover:bg-indigo-100 rounded-md text-sm"
                >Register</a
            >
        </li>
        <?php }?>
    </ul>
</nav>
<script>
    //wait till document has fully loaded
    $(function () {
        $("#searchBox").load("./components/searchBox.html");
        $("#user-drop-down").load("./components/userDropDown.php");
    });
</script>
