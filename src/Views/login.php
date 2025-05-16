<style>
	body {
		background-color: #f8f9fa;
	}
	.login-card {
		background: white;
		border-radius: 10px;
		box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		max-width: 400px;
		margin: 2rem auto;
		padding: 2rem;
	}
	.form-control:focus {
		box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
	}
</style>
<div class="container">
	<div class="login-card">
		<h2 class="text-center mb-4">Bejelentkezés</h2>

		<?php if (!empty($error)): ?>
			<div class="alert alert-danger">
				<i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
			</div>
		<?php endif; ?>

		<form method="POST">
			<div class="mb-3">
				<label for="email" class="form-label">
					<i class="fas fa-envelope me-2"></i>E-mail cím
				</label>
				<input type="email" class="form-control" id="email" name="email" required>
			</div>
			<div class="mb-4">
				<label for="password" class="form-label">
					<i class="fas fa-lock me-2"></i>Jelszó
				</label>
				<input type="password" class="form-control" id="password" name="password" required>
			</div>
			<div class="d-grid">
				<button type="submit" class="btn btn-primary">
					<i class="fas fa-sign-in-alt me-2"></i>Bejelentkezés
				</button>
			</div>
		</form>
	</div>
</div>