<?php include 'header.php'; ?>
			<?php
			if ( !empty( Web_Controller::$form_errors ) ) {
				foreach ( Web_Controller::$form_errors as $error ) {
					echo '<p class="error">' . $error . '</p>';
				}
			}
			?>
			<form method="post" action="" enctype="multipart/form-data">
				<p>
					<input type="radio" id="submit-type-url" name="submit-type" value="url" />
					<label for="readme-url">URL</label>
					<input type="text" id="readme-url" name="readme-url" />
				</p>

				<p>
					<input type="radio" id="submit-type-file" name="submit-type" value="file" />
					<label for="readme-file">File</label>
					<input type="file" id="readme-file" name="readme-file" />
				</p>

				<p>
					<input type="radio" id="submit-type-text" name="submit-type" value="text" />
					<label for="readme-txt">Text</label><br />
					<textarea name="readme-txt" id="readme-txt"></textarea>
				</p>

				<p>
					<input type="submit" value="Submit" />
				</p>
			</form>
<?php include 'footer.php'; ?>