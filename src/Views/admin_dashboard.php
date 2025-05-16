<div class="container py-5">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="dashboard-card p-4 mb-4">
				<h2 class="mb-4">Admin Dashboard</h2>
				<p class="mb-4">Üdvözlünk, <?php echo htmlspecialchars($user['email']); ?>!</p>
				
				<div class="d-grid gap-3">
					<a href="/" class="btn btn-primary">
						<i class="fas fa-home me-2"></i>Publikus főoldal
					</a>
					<a href="/admin/users" class="btn btn-primary">
						<i class="fas fa-users me-2"></i>Felhasználók kezelése
					</a>
					<a href="/admin/news" class="btn btn-primary">
						<i class="fas fa-blog me-2"></i>Hírek kezelése
					</a>
					<a href="/logout" class="btn btn-danger mt-3">
						<i class="fas fa-sign-out-alt me-2"></i>Kijelentkezés
					</a>
				</div>
			</div>
		</div>
	</div>
</div>