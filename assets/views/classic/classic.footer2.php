</main>
<input hidden type="text" id="PageJwtToken" value="<?= StaticFunctions::WatchReferer() ?>">
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="<?= PATH ?>assets/netflux/js/jquery-3.5.1.min.js" crossorigin="anonymous">
</script>
<script src="<?= PATH ?>assets/netflux/js/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
</script>
<script src="<?= PATH ?>assets/netflux/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
</script>
<script src="<?= PATH ?>assets/netflux/js/lazyload.js"></script>
<script src="<?= PATH ?>assets/netflux/js/barba.js"></script>
<script src="<?= PATH ?>assets/netflux/js/bootstrap-validate.js"></script>
<script src="<?= PATH ?>assets/netflux/js/core.js<?= (Debug) ? '?t=' . time() : null ?>"></script>
<script src="<?= PATH ?>assets/netflux/js/custom.js<?= (Debug) ? '?t=' . time() : null ?>"></script>
</body>

</html>