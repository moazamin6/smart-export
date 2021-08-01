<form action="{{route('install')}}" method="post">

    @csrf
    <label for="install">Enter Your Shop Name</label>
    <input type="text" id="install" name="shop_name">
    <input type="submit" value="Install">
</form>
