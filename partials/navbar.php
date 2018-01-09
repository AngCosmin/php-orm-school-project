<div class="text-center mb-5">
    <a href="../home"><button class="btn btn-primary">Home</button></a>   
    <a href="../my-news"><button class="btn btn-primary">My news</button></a>                       
    <a href="../friends-news"><button class="btn btn-primary">Friends news</button></a>   
    <a href="../users"><button class="btn btn-primary">Users</button></a>   

    <form action="/stiri/routes/web.php" method="POST">
        <input type="hidden" name="action" value="logout">

        <button type="submit" class="btn btn-sm btn-link">Logout</button>
    </form>
</div>