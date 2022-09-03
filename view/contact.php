<div class="articles">
	<article>
		<h1 class="pageTitleHeader">Contact</h1>

		<form action="/contact-send" method="post" class="contactForm">
			<label for="email">Email</label>
			<input type="email" name="email" required>
			<input type="text" name="website" class="websiteField">
			<label for="subject">Subject</label>
			<input type="text" name="subject" required>
			<label for="message">Message</label>
			<textarea name="message" required rows="10" cols="70"></textarea>
			<input type="submit" name="action" value="Send">
		</form>
	</article>
</div>