# Downloading and Modifying Schema.org Vocabulary Definition Files

## Download the Schema.org Vocabulary Definition Files

Download the CSV files for Types and for Properties at https://schema.org/docs/developers.html#defs.

1. Set the **File** dropdown to **schemaorg-current-https**.
2. Set the **Format** dropdown to **CSV**.
3. Set the **For** dropdown to **Types**.
4. Click **Download**.
5. Set the **For** dropdown to **Properties**.
6. Click **Download**.
7. Create a subfolder within **includes/schema/vocabulary-definition-files** for the current Schema.org version. Find the release number at https://schema.org/docs/releases.html.
8. Move the downloaded CSV files into that new subfolder.

An alternative method is to download the files directly from the schemaorg Github repository at https://github.com/schemaorg/schemaorg/tree/main/data/releases.

## Modify CSV Files

### Modify CSV File in Microsoft Excel

For each CSV file:

1. Open the CSV file in Microsoft Excel.
2. Delete the 'comment' column.
3. Save the CSV file.

### Modify CSV File Data in Visual Studio Code

For each CSV file:

1. Copy the data from the CSV file into a new PHP file.
2. Delete the header row.
3. Select the rows of data.
4. Open the Find and Replace tool.
5. Activate "Find in Selection."

## Prepare Properties for PHP Comparison

### Find and Replace, Round 1 — Move columns into arrays

#### Find

```
^(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+"))
```

1. id
4. label
7. subPropertyOf
10. equivalentProperty
13. subproperties
16. domainIncludes
19. rangeIncludes
22. inverseOf
25. supersedes
28. supersededBy
31. isPartOf

#### Replace All

```
				'$4' => array(
					'subPropertyOf' => array(
						'$7'
					),
					'equivalentProperty' => array(
						'$10'
					),
					'subproperties' => array(
						'$13'
					),
					'domainIncludes' => array(
						'$16'
					),
					'rangeIncludes' => array(
						'$19'
					),
					'inverseOf' => array(
						'$22'
					),
					'supersedes' => array(
						'$25'
					),
					'supersededBy' => array(
						'$28'
					),
					'isPartOf' => array(
						'$31'
					)
				),
```

### Find and Replace, Round 2

#### Find

Adjust the number of tabs in the positive lookbehind as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

```
(?<=^				'".+),
```

#### Replace All

```
'
				'
```

### Find and Replace, Round 3

#### Find

Adjust the number of tabs value as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

```
^						'"?([^'"]+)"?'
```

#### Replace All

```
						'$1'
```

### Find and Replace, Round 4

#### Find

```
'https://schema.org/
```

#### Replace All

```
'
```

### Find and Replace, Round 5

#### Find

```
(^\t+'.*' => )array\(
\t+''
\t+\)
```

#### Replace All

```
$1''
```

### Reduce Tabs

Reduce the base number of tabs to two tabs.

### Prepend and Append Code

Prepend the following code to the properties file:

```php

// Full list of properties from Schema.org

	$schema_org_properties = array(

```

Remove any trailing comma and append the following code to the properties file, replacing the version number and date with the relevant value:

```php

	);
```

## Prepare Types for PHP Comparison

### Find and Replace, Round 1 — Move columns into arrays

#### Find

```
^(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+"))$
```

1. id
4. label
7. subTypeOf
10. enumerationtype
13. equivalentClass
16. properties
19. subTypes
22. supersedes
25. supersededBy
28. isPartOf

#### Replace All

```
				'$4' => array(
					'subTypeOf' => array(
						'$7'
					),
					'enumerationtype' => array(
						'$10'
					),
					'equivalentClass' => array(
						'$13'
					),
					'properties' => array(
						'$16'
					),
					'subTypes' => array(
						'$19'
					),
					'supersedes' => array(
						'$22'
					),
					'supersededBy' => array(
						'$25'
					),
					'isPartOf' => array(
						'$28'
					)
				),
```

### Find and Replace, Round 2

#### Find

Adjust the number of tabs in the positive lookbehind as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

```
(?<=^				'".+),
```

#### Replace All

```
'
				'
```

### Find and Replace, Round 3

#### Find

Adjust the number of tabs value as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

```
^						'"?([^'"]+)"?'
```

#### Replace All

```
						'$1'
```

### Find and Replace, Round 4

#### Find

```
'https://schema.org/
```

#### Replace All

```
'
```

### Find and Replace, Round 5

#### Find

```
(^\t+'.*' => )array\(
\t+''
\t+\)
```

#### Replace All

```
$1''
```

### Find and Replace, Round 6

#### Find

```
(?<=^\t{6}'.*), https:\/\/schema.org\/
```

#### Replace All

```
',\n\t\t\t\t\t\t'
```

### Reduce Tabs

Reduce the base number of tabs to two tabs.

### Prepend and Append Code

Prepend the following code to the types file, replacing the version number and date with the relevant value:

```php

// Full list of types from Schema.org

	$schema_org_types = array(

```

Remove any trailing comma and append the following code to the types file:

```php

	);

```

## Create a Combined PHP File for Comparison

Create a new PHP file in the version subfolder. Name it ```schema-org_v27-02.php```, replacing the version number with the relevant value.

Add the following code to the file:

```php
<?php

/*
 * Schema.org Version 27.02 (2024-07-01)
 * Types and Properties
 */

```

Append the code from the types file.

Append the code from the properties file.

## Compare the New File Against the Current File

1. In the Explorer tab of Visual Studio Code, select ```templates/parts/vars/page/schema/schema-org.php``` _then_ the combined PHP file.
2. Right-click either file and click **Compare Selected**.
3. Verify that the changes in the new file match the syntax found in the ```templates/parts/vars/page/schema/schema-org.php```.
4. Make any necessary corrections to the new file.

## Update Existing File

1. Replace the code in ```templates/parts/vars/page/schema/schema-org.php``` with the code from the new file.
2. Delete the PHP files created for this process.
