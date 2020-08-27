# Xliff Parser

This library is a simple, agnostic Xliff parser specifically written for [Matecat](https://www.matecat.com).

## Xliff Support

Xliff supported versions:

* [1.0](http://www.oasis-open.org/committees/xliff/documents/contribution-xliff-20010530.htm)
* [1.1](http://www.oasis-open.org/committees/xliff/documents/xliff-specification.htm)
* [1.2](http://docs.oasis-open.org/xliff/v1.2/os/xliff-core.html)
* [2.0](http://docs.oasis-open.org/xliff/xliff-core/v2.0/xliff-core-v2.0.html#data)

## Usage

Use `XliffParser` to convert a xliff file into an array as shown below:

```php
//
use Matecat\XliffParser\XliffParser;

$parsed = XliffParser::toArray('path/to/your/file.xliff');
```

In case of invalid or not supported xliff file an empty array will be returned.

## Segmentation differences 

Xliff 1.* and 2.0 use a completely different segment segmentation approach. Take a look at the two examples below:

```xml
<!--Segmentation in 1.2 -->
<trans-unit id="u1">
    <source>Sentence 1. Sentence 2.</source>
    <seg-source>
        <mrk mtype="seg" mid="1">Sentence 1. </mrk>
        <mrk mtype="seg" mid="2">Sentence 2.</mrk></seg-source>
    <target>
        <mrk mtype="seg" mid="1">Phrase 1.</mrk>
        <mrk mtype="seg" mid="2">Phrase 2.</mrk>
    </target>
</trans-unit>
```

```xml
<!--Segmentation in 2.0-->
<unit id="u1">
    <segment id="1">
        <source>Sentence 1. </source>
        <target>Phrase 1. </target>
    </segment>
    <segment id="2">
        <source>Sentence 2.</source>
        <target>Phrase 2.</target>
    </segment>
</unit>
```

This will be reflected in `trans-unit` array generated by `XliffParser` class: **in v2 `seg-source` `seg-target` keys are totally abolished**. 

## Output skeleton

As above mentioned, since there are some differences between xliff v1 and v2, the array output obtained from the parser will be slightly different:

| Parent element | Key             | V1 | V2 |
|----------------|-----------------|----|----|
| attr           | datatype        | *  |    |
| attr           | original        | *  | *  |
| attr           | source-language | *  | *  |
| attr           | target-language | *  | *  |
| attr           | custom          | *  |    |
| reference      |                 | *  |    |
| notes          |                 |    | *  |
| trans-units    | alt-trans       | *  |    |
| trans-units    | attr            | *  | *  |
| trans-units    | context-group   | *  |    |
| trans-units    | locked          | *  |    |
| trans-units    | notes           | *  | *  |
| trans-units    | original-data   |    | *  |
| trans-units    | source          | *  | *  |
| trans-units    | target          | *  | *  |
| trans-units    | seg-source      | *  |    |
| trans-units    | seg-target      | *  |    |

For more info please referer to this [document](https://www.localizationworld.com/lwdub2014/feisgiltt/slides/Yves_Differences.pdf).

## Examples

In this section you can find two full examples of parsed xliff document.

### xliff v1 (1.0, 1.1, 1.2) 

Input:

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<xliff
	xmlns="urn:oasis:names:tc:xliff:document:1.2"
	xmlns:its="http://www.w3.org/2005/11/its"
	xmlns:itsxlf="http://www.w3.org/ns/its-xliff/"
	xmlns:okp="okapi-framework:xliff-extensions" its:version="2.0" version="1.2">
	<file datatype="x-xlf" original="Ebay-like-small-file-edited.xlf" source-language="hy-am" target-language="fr-fr" tool-id="matecat-converter">
		<header>
			<reference>
				<internal-file form="base64">PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHhsaWZmIHhtbG5zPSJ1cm46b2FzaXM6bmFtZXM6dGM6eGxpZmY6ZG9jdW1lbnQ6MS4yIiB2ZXJzaW9uPSIxLjIiPgo8ZmlsZSBvcmlnaW5hbD0iIiBzb3VyY2UtbGFuZ3VhZ2U9ImVuLVVTIiB0YXJnZXQtbGFuZ3VhZ2U9InB0LUJSIiBkYXRhdHlwZT0icGxhaW50ZXh0IiBtdD0iZU1UIj4KPGhlYWRlcj48L2hlYWRlcj4KPGJvZHk+Cgk8dHJhbnMtdW5pdCBjYXQxPSJBbnRpcXVlcyIgY2F0Mj0iQW50aXF1aXRpZXMiIGlkPSIyNTE5NzE1NTEwNjUiPgoJCTxzb3VyY2UgeG1sOmxhbmc9ImVuLVVTIj5BbiBFbmdsaXNoIHN0cmluZzwvc291cmNlPgoJCTx0YXJnZXQgeG1sOmxhbmc9InB0LUJSIj5UaGlzIGlzIEJyYXppbGlhbiB0ZXh0IDE8L3RhcmdldD4KCQk8bm90ZT5UaGlzIGlzIGEgY29tbWVudDwvbm90ZT4KCQk8bm90ZT5UaGlzIGlzIGEgY29tbWVudCBudW1iZXIgdHdvPC9ub3RlPgoJCTxub3RlPlRoaXMgaXMgYSBjb21tZW50IG51bWJlciB0aHJlZTwvbm90ZT4KCTwvdHJhbnMtdW5pdD4KICA8dHJhbnMtdW5pdCBjYXQxPSJBbnRpcXVlcyIgY2F0Mj0iQW50aXF1aXRpZXMiIGlkPSIyNTE5NzE1NTEwNjYiPgoJCTxzb3VyY2UgeG1sOmxhbmc9ImVuLVVTIj5UaGlzIHVuaXQgaGFzIGEgY29tbWVudCB0b288L3NvdXJjZT4KCQk8dGFyZ2V0IHhtbDpsYW5nPSJwdC1CUiI+QW5vdGhlciB0cmFuc2xhdGVkIHRleHQ8L3RhcmdldD4KCTwvdHJhbnMtdW5pdD4KICA8dHJhbnMtdW5pdCBjYXQxPSJBbnRpcXVlcyIgY2F0Mj0iQW50aXF1aXRpZXMiIGlkPSIyNTE5NzE1NTEwNjciPgoJCTxzb3VyY2UgeG1sOmxhbmc9ImVuLVVTIj5BbiBFbmdsaXNoIHN0cmluZzwvc291cmNlPgoJCTx0YXJnZXQgeG1sOmxhbmc9InB0LUJSIj5UaGlzIGlzIEJyYXppbGlhbiB0ZXh0IDE8L3RhcmdldD4KCQk8bm90ZT5UaGlzIGlzIGFub3RoZXIgY29tbWVudDwvbm90ZT4KCTwvdHJhbnMtdW5pdD4KPC9ib2R5Pgo8L2ZpbGU+CjwveGxpZmY+Cg==</internal-file>
			</reference>
		</header>
		<body/>
	</file>
	<file datatype="x-rkm" original="manifest.rkm" source-language="hy-am" target-language="fr-fr" tool-id="matecat-converter">
		<header>
			<reference>
				<internal-file form="base64">PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjwhLS09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PS0tPg0KPCEtLVBMRUFTRSwgRE8gTk9UIFJFTkFNRSwgTU9WRSwgTU9ESUZZIE9SIEFMVEVSIElOIEFOWSBXQVkgVEhJUyBGSUxFLS0+DQo8IS0tPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0tLT4NCjxtYW5pZmVzdCB2ZXJzaW9uPSIyIiBsaWJWZXJzaW9uPSIiIHByb2plY3RJZD0iTkM1QzkzQURFIiBwYWNrYWdlSWQ9IjY4ODc2NjIyLTQzZWItNDdiYy1hY2VmLWFmNjNlNWQwOTE5OSIgc291cmNlPSJoeS1hbSIgdGFyZ2V0PSJmci1mciIgb3JpZ2luYWxTdWJEaXI9Im9yaWdpbmFsIiBza2VsZXRvblN1YkRpcj0ic2tlbGV0b24iIHNvdXJjZVN1YkRpcj0id29yayIgdGFyZ2V0U3ViRGlyPSJ3b3JrIiBtZXJnZVN1YkRpcj0iZG9uZSIgdG1TdWJEaXI9IiIgZGF0ZT0iMjAxNS0xMC0wNiAxNjo1ODowMCswMDAwIiB1c2VBcHByb3ZlZE9ubHk9IjAiIHVwZGF0ZUFwcHJvdmVkRmxhZz0iMCI+DQo8Y3JlYXRvclBhcmFtZXRlcnM+PC9jcmVhdG9yUGFyYW1ldGVycz4NCjxkb2MgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgZG9jSWQ9IjEiIGV4dHJhY3Rpb25UeXBlPSJ4bGlmZiIgcmVsYXRpdmVJbnB1dFBhdGg9IkViYXktbGlrZS1zbWFsbC1maWxlLWVkaXRlZC54bGYiIGZpbHRlcklkPSJva2ZfeGxpZmYiIGlucHV0RW5jb2Rpbmc9InV0Zi04IiByZWxhdGl2ZVRhcmdldFBhdGg9IkViYXktbGlrZS1zbWFsbC1maWxlLWVkaXRlZC5vdXQueGxmIiB0YXJnZXRFbmNvZGluZz0iVVRGLTgiIHNlbGVjdGVkPSIxIj5JM1l4Q25WelpVTjFjM1J2YlZCaGNuTmxjaTVpUFhSeWRXVUtabUZqZEc5eWVVTnNZWE56UFdOdmJTNWpkR011ZDNOMGVDNXpkR0Y0TGxkemRIaEpibkIxZEVaaFkzUnZjbmtLWm1Gc2JHSmhZMnRVYjBsRUxtSTlabUZzYzJVS1pYTmpZWEJsUjFRdVlqMW1ZV3h6WlFwaFpHUlVZWEpuWlhSTVlXNW5kV0ZuWlM1aVBYUnlkV1VLYjNabGNuSnBaR1ZVWVhKblpYUk1ZVzVuZFdGblpTNWlQV1poYkhObENtOTFkSEIxZEZObFoyMWxiblJoZEdsdmJsUjVjR1V1YVQwekNtbG5ibTl5WlVsdWNIVjBVMlZuYldWdWRHRjBhVzl1TG1JOVptRnNjMlVLWVdSa1FXeDBWSEpoYm5NdVlqMW1ZV3h6WlFwaFpHUkJiSFJVY21GdWMwZE5iMlJsTG1JOWRISjFaUXBsWkdsMFFXeDBWSEpoYm5NdVlqMW1ZV3h6WlFwcGJtTnNkV1JsUlhoMFpXNXphVzl1Y3k1aVBYUnlkV1VLYVc1amJIVmtaVWwwY3k1aVBYUnlkV1VLWW1Gc1lXNWpaVU52WkdWekxtSTlkSEoxWlFwaGJHeHZkMFZ0Y0hSNVZHRnlaMlYwY3k1aVBXWmhiSE5sQ25SaGNtZGxkRk4wWVhSbFRXOWtaUzVwUFRBS2RHRnlaMlYwVTNSaGRHVldZV3gxWlQxdVpXVmtjeTEwY21GdWMyeGhkR2x2YmdwaGJIZGhlWE5WYzJWVFpXZFRiM1Z5WTJVdVlqMW1ZV3h6WlFweGRXOTBaVTF2WkdWRVpXWnBibVZrTG1JOWRISjFaUXB4ZFc5MFpVMXZaR1V1YVQwd0NuVnpaVk5rYkZoc2FXWm1WM0pwZEdWeUxtSTlabUZzYzJVPTwvZG9jPg0KPC9tYW5pZmVzdD4=</internal-file>
			</reference>
		</header>
		<body/>
	</file>
	<file datatype="x-plaintext" original="" source-language="hy-am" target-language="fr-fr">
		<body>
			<trans-unit id="251971551065">
				<source xml:lang="hy-am">An English string</source>
				<seg-source>
					<g id="1">
						<mrk mid="0" mtype="seg">An English string with g tags</mrk>
					</g>
				</seg-source>
				<target xml:lang="fr-fr">
					<g id="1">
						<mrk mid="0" mtype="seg">An English string with g tags</mrk>
					</g>
				</target>
				<note>This is a comment
---
This is a comment number two
---
This is a comment number three</note>
			</trans-unit>
			<trans-unit id="251971551066">
				<source xml:lang="hy-am">This unit has a comment too</source>
				<seg-source>
					<mrk mid="0" mtype="seg">This unit has a comment too</mrk>
				</seg-source>
				<target xml:lang="fr-fr">
					<mrk mid="0" mtype="seg">This unit has a comment too</mrk>
				</target>
			</trans-unit>
			<trans-unit id="251971551067">
				<source xml:lang="hy-am">An English string</source>
				<seg-source>
					<mrk mid="0" mtype="seg">An English string</mrk>
				</seg-source>
				<target xml:lang="fr-fr">
					<mrk mid="0" mtype="seg">An English string</mrk>
				</target>
				<note>This is another comment</note>
			</trans-unit>
		</body>
	</file>
</xliff>
```

Output:
```php
$output = [
    'parser-warnings' =>
        [
            0 => 'Input identified as ASCII ans converted UTF-8. May not be a problem if the content is English only',
        ],
    'files' =>
        [
            1 => [
                'attr' => [
                    'data-type'       => 'x-xlf',
                    'original'        => 'Ebay-like-small-file-edited.xlf',
                    'source-language' => 'hy-am',
                    'target-language' => 'fr-fr',
                ],
                'reference' => [
                    0 => [
                        'form-type' => 'base64',
                        'base64'    => 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPHhsaWZmIHhtbG5zPSJ1cm46b2FzaXM6bmFtZXM6dGM6eGxpZmY6ZG9jdW1lbnQ6MS4yIiB2ZXJzaW9uPSIxLjIiPgo8ZmlsZSBvcmlnaW5hbD0iIiBzb3VyY2UtbGFuZ3VhZ2U9ImVuLVVTIiB0YXJnZXQtbGFuZ3VhZ2U9InB0LUJSIiBkYXRhdHlwZT0icGxhaW50ZXh0IiBtdD0iZU1UIj4KPGhlYWRlcj48L2hlYWRlcj4KPGJvZHk+Cgk8dHJhbnMtdW5pdCBjYXQxPSJBbnRpcXVlcyIgY2F0Mj0iQW50aXF1aXRpZXMiIGlkPSIyNTE5NzE1NTEwNjUiPgoJCTxzb3VyY2UgeG1sOmxhbmc9ImVuLVVTIj5BbiBFbmdsaXNoIHN0cmluZzwvc291cmNlPgoJCTx0YXJnZXQgeG1sOmxhbmc9InB0LUJSIj5UaGlzIGlzIEJyYXppbGlhbiB0ZXh0IDE8L3RhcmdldD4KCQk8bm90ZT5UaGlzIGlzIGEgY29tbWVudDwvbm90ZT4KCQk8bm90ZT5UaGlzIGlzIGEgY29tbWVudCBudW1iZXIgdHdvPC9ub3RlPgoJCTxub3RlPlRoaXMgaXMgYSBjb21tZW50IG51bWJlciB0aHJlZTwvbm90ZT4KCTwvdHJhbnMtdW5pdD4KICA8dHJhbnMtdW5pdCBjYXQxPSJBbnRpcXVlcyIgY2F0Mj0iQW50aXF1aXRpZXMiIGlkPSIyNTE5NzE1NTEwNjYiPgoJCTxzb3VyY2UgeG1sOmxhbmc9ImVuLVVTIj5UaGlzIHVuaXQgaGFzIGEgY29tbWVudCB0b288L3NvdXJjZT4KCQk8dGFyZ2V0IHhtbDpsYW5nPSJwdC1CUiI+QW5vdGhlciB0cmFuc2xhdGVkIHRleHQ8L3RhcmdldD4KCTwvdHJhbnMtdW5pdD4KICA8dHJhbnMtdW5pdCBjYXQxPSJBbnRpcXVlcyIgY2F0Mj0iQW50aXF1aXRpZXMiIGlkPSIyNTE5NzE1NTEwNjciPgoJCTxzb3VyY2UgeG1sOmxhbmc9ImVuLVVTIj5BbiBFbmdsaXNoIHN0cmluZzwvc291cmNlPgoJCTx0YXJnZXQgeG1sOmxhbmc9InB0LUJSIj5UaGlzIGlzIEJyYXppbGlhbiB0ZXh0IDE8L3RhcmdldD4KCQk8bm90ZT5UaGlzIGlzIGFub3RoZXIgY29tbWVudDwvbm90ZT4KCTwvdHJhbnMtdW5pdD4KPC9ib2R5Pgo8L2ZpbGU+CjwveGxpZmY+Cg==',
                    ],
                ],
            ],
            2 => [
                'attr' => [
                    'data-type'       => 'x-rkm',
                    'original'        => 'manifest.rkm',
                    'source-language' => 'hy-am',
                    'target-language' => 'fr-fr',
                ],
                'reference' => [
                    0 => [
                        'form-type' => 'base64',
                        'base64'    => 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4NCjwhLS09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PS0tPg0KPCEtLVBMRUFTRSwgRE8gTk9UIFJFTkFNRSwgTU9WRSwgTU9ESUZZIE9SIEFMVEVSIElOIEFOWSBXQVkgVEhJUyBGSUxFLS0+DQo8IS0tPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0tLT4NCjxtYW5pZmVzdCB2ZXJzaW9uPSIyIiBsaWJWZXJzaW9uPSIiIHByb2plY3RJZD0iTkM1QzkzQURFIiBwYWNrYWdlSWQ9IjY4ODc2NjIyLTQzZWItNDdiYy1hY2VmLWFmNjNlNWQwOTE5OSIgc291cmNlPSJoeS1hbSIgdGFyZ2V0PSJmci1mciIgb3JpZ2luYWxTdWJEaXI9Im9yaWdpbmFsIiBza2VsZXRvblN1YkRpcj0ic2tlbGV0b24iIHNvdXJjZVN1YkRpcj0id29yayIgdGFyZ2V0U3ViRGlyPSJ3b3JrIiBtZXJnZVN1YkRpcj0iZG9uZSIgdG1TdWJEaXI9IiIgZGF0ZT0iMjAxNS0xMC0wNiAxNjo1ODowMCswMDAwIiB1c2VBcHByb3ZlZE9ubHk9IjAiIHVwZGF0ZUFwcHJvdmVkRmxhZz0iMCI+DQo8Y3JlYXRvclBhcmFtZXRlcnM+PC9jcmVhdG9yUGFyYW1ldGVycz4NCjxkb2MgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgZG9jSWQ9IjEiIGV4dHJhY3Rpb25UeXBlPSJ4bGlmZiIgcmVsYXRpdmVJbnB1dFBhdGg9IkViYXktbGlrZS1zbWFsbC1maWxlLWVkaXRlZC54bGYiIGZpbHRlcklkPSJva2ZfeGxpZmYiIGlucHV0RW5jb2Rpbmc9InV0Zi04IiByZWxhdGl2ZVRhcmdldFBhdGg9IkViYXktbGlrZS1zbWFsbC1maWxlLWVkaXRlZC5vdXQueGxmIiB0YXJnZXRFbmNvZGluZz0iVVRGLTgiIHNlbGVjdGVkPSIxIj5JM1l4Q25WelpVTjFjM1J2YlZCaGNuTmxjaTVpUFhSeWRXVUtabUZqZEc5eWVVTnNZWE56UFdOdmJTNWpkR011ZDNOMGVDNXpkR0Y0TGxkemRIaEpibkIxZEVaaFkzUnZjbmtLWm1Gc2JHSmhZMnRVYjBsRUxtSTlabUZzYzJVS1pYTmpZWEJsUjFRdVlqMW1ZV3h6WlFwaFpHUlVZWEpuWlhSTVlXNW5kV0ZuWlM1aVBYUnlkV1VLYjNabGNuSnBaR1ZVWVhKblpYUk1ZVzVuZFdGblpTNWlQV1poYkhObENtOTFkSEIxZEZObFoyMWxiblJoZEdsdmJsUjVjR1V1YVQwekNtbG5ibTl5WlVsdWNIVjBVMlZuYldWdWRHRjBhVzl1TG1JOVptRnNjMlVLWVdSa1FXeDBWSEpoYm5NdVlqMW1ZV3h6WlFwaFpHUkJiSFJVY21GdWMwZE5iMlJsTG1JOWRISjFaUXBsWkdsMFFXeDBWSEpoYm5NdVlqMW1ZV3h6WlFwcGJtTnNkV1JsUlhoMFpXNXphVzl1Y3k1aVBYUnlkV1VLYVc1amJIVmtaVWwwY3k1aVBYUnlkV1VLWW1Gc1lXNWpaVU52WkdWekxtSTlkSEoxWlFwaGJHeHZkMFZ0Y0hSNVZHRnlaMlYwY3k1aVBXWmhiSE5sQ25SaGNtZGxkRk4wWVhSbFRXOWtaUzVwUFRBS2RHRnlaMlYwVTNSaGRHVldZV3gxWlQxdVpXVmtjeTEwY21GdWMyeGhkR2x2YmdwaGJIZGhlWE5WYzJWVFpXZFRiM1Z5WTJVdVlqMW1ZV3h6WlFweGRXOTBaVTF2WkdWRVpXWnBibVZrTG1JOWRISjFaUXB4ZFc5MFpVMXZaR1V1YVQwd0NuVnpaVk5rYkZoc2FXWm1WM0pwZEdWeUxtSTlabUZzYzJVPTwvZG9jPg0KPC9tYW5pZmVzdD4=',
                    ],
                ],
            ],
            3 => [
                'attr' => [
                    'data-type'       => 'x-plaintext',
                    'original'        => '',
                    'source-language' => 'hy-am',
                    'target-language' => 'fr-fr',
                ],
                'trans-units' => [
                    1 => [
                        'attr' => [
                            'id' => '251971551065',
                        ],
                        'notes' => [
                            0 => [
                                'raw-content' => 'This is a comment
---
This is a comment number two
---
This is a comment number three',
                                ],
                    ],
                    'source' => [
                        'raw-content' => 'An English string',
                        'attr' => [
                            'xml:lang' => 'hy-am',
                         ],
                    ],
                    'seg-source' =>  [
                        0 => [
                            'mid'           => '0',
                            'ext-prec-tags' => '<g id="1">',
                            'raw-content'   => 'An English string with g tags',
                            'ext-succ-tags' => '</g>',
                        ],
                    ],
                    'target' => [
                        'raw-content' => '<g id="1"><mrk mid="0" mtype="seg">An English string with g tags</mrk></g>',
                        'attr' => [
                            'xml:lang' => 'fr-fr',
                        ],
                    ],
                    'seg-target' => [
                        0 => [
                            'mid'           => '0',
                            'ext-prec-tags' => '<g id="1">',
                            'raw-content'   => 'An English string with g tags',
                            'ext-succ-tags' => '</g>',
                        ],
                    ],
                ],
                    2 => [
                        'attr' => [
                            'id' => '251971551066',
                        ],
                        'notes' => [],
                        'source' => [
                            'raw-content' => 'This unit has a comment too',
                            'attr' => [
                                'xml:lang' => 'hy-am',
                            ],
                        ],
                        'seg-source' => [
                                0 => [
                                'mid'           => '0',
                                'ext-prec-tags' => '',
                                'raw-content'   => 'This unit has a comment too',
                                'ext-succ-tags' => '',
                            ],
                        ],
                        'target' => [
                            'raw-content' => '<mrk mid="0" mtype="seg">This unit has a comment too</mrk>',
                            'attr' => [
                                'xml:lang' => 'fr-fr',
                            ],
                        ],
                        'seg-target' => [
                            0 => [
                                'mid'           => '0',
                                'ext-prec-tags' => '',
                                'raw-content'   => 'This unit has a comment too',
                                'ext-succ-tags' => '',
                            ],
                        ],
                    ],
                    3 => [
                        'attr' => [
                            'id' => '251971551067',
                        ],
                        'notes' => [
                            0 => [
                                'raw-content' => 'This is another comment',
                            ],
                        ],
                        'source' => [
                            'raw-content' => 'An English string',
                            'attr' =>[
                                'xml:lang' => 'hy-am',
                            ],
                        ],
                        'seg-source' => [
                            0 => [
                                'mid'           => '0',
                                'ext-prec-tags' => '',
                                'raw-content'   => 'An English string',
                                'ext-succ-tags' => '',
                            ],
                        ],
                        'target' => [
                            'raw-content' => '<mrk mid="0" mtype="seg">An English string</mrk>',
                            'attr' => [
                                'xml:lang' => 'fr-fr',
                            ],
                        ],
                        'seg-target' => [
                            0 => [
                                'mid'           => '0',
                                'ext-prec-tags' => '',
                                'raw-content'   => 'An English string',
                                'ext-succ-tags' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];
```

### xliff v2 (2.0) 

Input:

```xml
<xliff xmlns="urn:oasis:names:tc:xliff:document:2.0"
    version="2.0"
    srcLang="en"
    trgLang="fr"
    >
    <file id="f1" original="389108a4-rtapi.xml">
        <notes>
            <note id="n1">note for file.</note>
            <note id="n2">note2 for file.</note>
            <note id="n3">
                {
                    "key": "value",
                    "key2": "value2",
                    "key3": "value3"
                }
            </note>
        </notes>
        <unit id="u1" translate="test">
            <my:elem xmlns:my="myNamespaceURI" id="x1">data</my:elem>
            <notes>
                <note id="n1">note for unit</note>
                <note id="n2">another note for unit.</note>
                <note id="n3">
                    {
                        "key": "value",
                        "key2": "value2",
                        "key3": "value3"
                    }
                </note>
            </notes>
            <segment id="s1">
                <source>
                    <pc id="1">Hello <mrk id="m1" type="term">World</mrk>!</pc>
                </source>
                <target>
                    <pc id="1">Bonjour le <mrk id="m1" type="term">Monde</mrk> !</pc>
                </target>
            </segment>
        </unit>
        <unit id="u2">
            <my:elem xmlns:my="myNamespaceURI" id="x2">data</my:elem>
            <notes>
                <note id="n1">note for unit2</note>
                <note id="n2">another note for unit2.</note>
                <note id="n3">
                    {
                        "key": "value",
                        "key2": "value2",
                        "key3": "value3"
                    }
                </note>
            </notes>
            <segment id="s2">
                <source>
                    <pc id="2">Hello <mrk id="m2" type="term">World2</mrk>!</pc>
                </source>
                <target>
                    <pc id="2">Bonjour le <mrk id="m2" type="term">Monde2</mrk> !</pc>
                </target>
            </segment>
        </unit>
    </file>
</xliff>
```

Output:

```php

$output = [
    'parser-warnings' =>
        [
            0 => 'Input identified as ASCII ans converted UTF-8. May not be a problem if the content is English only',
        ],
    'files' =>
        [
            1 => [
            'attr' =>
                [
                    'original'        => '389108a4-rtapi.xml',
                    'source-language' => 'en',
                    'target-language' => 'fr',
                ],
            'notes' =>
                [
                    0 => ['raw-content' => 'note for file.', ],
                    1 => ['raw-content' => 'note2 for file.',],
                    2 => ['json' => '{
                            "key": "value",
                            "key2": "value2",
                            "key3": "value3"
                        }',
                    ],
                ],
            'trans-units' =>
                [
                    1 => [
                        'attr' => [
                            'id' => 'u1',
                            'translate' => 'test',
                        ],
                        'notes' => [
                            0 => ['raw-content' => 'note for unit',],
                            1 => ['raw-content' => 'another note for unit.',],
                            2 => ['json' => '{
                                    "key": "value",
                                    "key2": "value2",
                                    "key3": "value3"
                                }',
                            ],
                        ],
                        'original-data' => [],
                         'source' => [
                             'content' => '&lt;pc id="1"&gt;Hello <mrk id="m1" type="term">World</mrk>!&lt;/pc&gt;',
                             'attr'    => [],
                         ],
                         'target' => [
                            'content' => '&lt;pc id="1"&gt;Bonjour le <mrk id="m1" type="term">Monde</mrk> !&lt;/pc&gt;',
                             'attr'    => [],
                         ],
                    ],
                    2 => [
                        'attr' => [
                            'id' => 'u2',
                        ],
                        'notes' => [
                            0 => [ 'raw-content' => 'note for unit2', ],
                            1 => [ 'raw-content' => 'another note for unit2.', ],
                            2 => [ 'json' => '{
                                "key": "value",
                                "key2": "value2",
                                "key3": "value3"
                            }',
                         ],
                    ],
                    'original-data' => [],
                    'source' => [
                            'content' => '&lt;pc id="2"&gt;Hello <mrk id="m2" type="term">World2</mrk>!&lt;/pc&gt;',
                            'attr'    => [],
                    ],
                    'target' => [
                            'content' => '&lt;pc id="2"&gt;Bonjour le <mrk id="m2" type="term">Monde2</mrk> !&lt;/pc&gt;',
                            'attr'    => [],
                    ],
                ],
            ],
        ]
    ]
];
```

## Support

If you found an issue or had an idea please refer [to this section](https://github.com/mauretto78/xliff-parser/issues).

## Authors

* **Mauro Cassani** - [github](https://github.com/mauretto78)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
