<?php
if(isset($_SESSION["install"]))
{
/* CREATE ADMIN TABLE */
mysqli_query($con, "CREATE TABLE `admin` (
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
/* CREATE FACEBOOK TABLE */
mysqli_query($con, "CREATE TABLE `facebook` (
  `fb_mode` int(11) NOT NULL,
  `page_id` bigint(20) NOT NULL,
  `content` text NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

if(mysqli_num_rows(mysqli_query($con, "select * from admin where 1")) == 0)
{
	/* INSERT TO ADMIN TABLE */
	mysqli_query($con, "INSERT INTO `admin` (`username`, `password`) VALUES
	('admin', 'vynghia1308');");
}

if(mysqli_num_rows(mysqli_query($con, "select * from facebook where 1")) == 0)
{
	/* INSERT TO FACEBOOK TABLE */
	mysqli_query($con, "INSERT INTO `facebook` (`fb_mode`, `page_id`, `content`, `token`) VALUES
	(0, 0, 'W1dlYiBDb25mZXNzaW9uXQ0KLS0tLQ0KDQpO4buZaSBkdW5nIGNvbmZlc3Npb246IHt7Y29udGVudH19', 'null');");
}

/* CREATE POST TABLE */
mysqli_query($con, "CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `approval` int(1) NOT NULL,
  `posted_page` int(11) NOT NULL,
  `image` text NOT NULL,
  `time` datetime NOT NULL,
  `time_approval` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
/* SET `id` IS PRIMARY KEY FOR POST TABLE */
mysqli_query($con, "ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);");
/* SET AUTO_INCREMENT FOR POST TABLE */
mysqli_query($con, "ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
  
/* CHECK MYSQL */  
if(mysqli_error($con))
	echo mysqli_error($con);
else
	echo true;

mysqli_close($con);
}