# Downloading and Modifying Schema.org Vocabulary Definition Files

## Download the Schema.org Vocabulary Definition Files

Download the CSV files for Types and for Properties at https://schema.org/docs/developers.html#defs.

1. Set the **File** dropdown to **schemaorg-current-https**.
2. Set the **Format** dropdown to **CSV**.
3. Set the **For** dropdown to **Types**.
4. Click **Download**.
5. Set the **For** dropdown to **Properties**.
6. Click **Download**.
7. Create a subfolder within **includes/schema/vocabulary-definition-files** for the current Schema.org version.
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

### Round 1

#### Find

<pre><code>^(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+"))</code></pre>

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

<pre><code>				'$4' => array(
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
				),</code></pre>

### Round 2

#### Find

Adjust the number of tabs in the positive lookbehind as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

<pre><code>(?<=^				'".+), </code></pre>

#### Replace All

<pre><code>'
				'</code></pre>

### Round 3

#### Find

Adjust the number of tabs value as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

<pre><code>^						'"?([^'"]+)"?'</code></pre>

#### Replace All

<pre><code>						'$1'</code></pre>

### Round 4

#### Find

<pre><code>'https://schema.org/</code></pre>

#### Replace All

<pre><code>'</code></pre>

### Round 5

#### Find

<pre><code>(^\t+'.*' => )array\(
				''
			\)</code></pre>

#### Replace All

<pre><code>$1''</code></pre>

## Prepare Types for PHP Comparison

### Round 1

#### Find

<pre><code>^(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+")),(([^,"]*)|("[^"]+"))$</code></pre>

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

<pre><code>				'$4' => array(
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
				),</code></pre>

### Round 2

#### Find

Adjust the number of tabs in the positive lookbehind as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

<pre><code>(?<=^				'".+), </code></pre>

#### Replace All

<pre><code>'
				'</code></pre>

### Round 3

#### Find

Adjust the number of tabs value as needed. If adjusting the number, adjust the number of tabs in the Replace All value to match it.

<pre><code>^						'"?([^'"]+)"?'</code></pre>

#### Replace All

<pre><code>						'$1'</code></pre>

### Round 4

#### Find

<pre><code>'https://schema.org/</code></pre>

#### Replace All

<pre><code>'</code></pre>

### Round 5

#### Find

<pre><code>(^\t+'.*' => )array\(
				''
			\)</code></pre>

#### Replace All

<pre><code>$1''</code></pre>