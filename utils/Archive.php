<?php

class Archive
{
	public static function includeAll(string $path, string $extension, array $exclude = []): void
	{
		if(is_dir($path))
		{
			$archives = scandir($path);
			if(!empty($archives))
			{
				foreach($archives as $archive)
				{
					if($archive !== "." && $archive !== ".." && !in_array($archive, $exclude))
					{
						if(pathinfo($archive, PATHINFO_EXTENSION) === $extension)
						{
							include_once $path . '/' . $archive;
						}
					}
				}
			}
		}
	}
}