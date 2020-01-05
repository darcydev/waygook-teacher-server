<?php
class Constants
{
	public static $passwordsDoNoMatch = "Your passwords don't match";
	public static $passwordNotAlphanumeric = "Your password can only contain numbers and letters";
	public static $passwordCharacters = "Your password must be between 5 and 50 characters";
	public static $passwordIncorrect = "Your password is incorrect, please try again";
	public static $emailInvalid = "Email is invalid";
	public static $emailTaken = "This email is already in use";
	public static $lastNameCharacters = "Your last name must be between 2 and 25 characters";
	public static $firstNameCharacters = "Your first name must be between 2 and 25 characters";

	public static $loginFailed = "Your username or password was incorrect";

	public static $descriptionCharacters = "The description must be less than 5,000 characters";

	public static $invalidFileExtension = "The file must a JPEG or PNG file";
	public static $invalidFileSize = "The file must be less than 2MB";
	public static $invalidFileName = "The file already exists. Please rename the file";

	public static $invalidLessonDate = "The lesson must be between -10 and +60 days from today";
	public static $invalidBalanceForLesson = "Sorry, this isn't sufficient balance for this lesson duration";
}