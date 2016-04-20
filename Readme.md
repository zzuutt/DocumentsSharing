# Documents Sharing
for Thelia >= 2.2.0

This module allows to share documents with a customer or/and customer group.

You can set a download limit date and if the file should be deleted at the end.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is DocumentsSharing.
* Activate it in your thelia administration panel


## Usage

In the tools menu, a new page displays comments and let you manage them.

In the front office, an integration is provided for the default template. It uses hooks, so it's activated by default.

A log file is created for monitoring

## Hook

If your module use one or more hook, fill this part. Explain which hooks are used.


## Loop

The module provides a new loop : **documents-sharing**, **documents-sharing-document**, **documents-sharing-groupe**

[documents-sharing]
### Input arguments

|Argument |Description |
|---      |--- |
|**id** |  A single or a list of ids. |
|**share_key** | A single share key |
|**document_id** | A single or a list of document ids. |
|**customer_id** | A single or a list of customer ids. |
|**groupe_id** |  A single or a list of group ids. |
|**delete_file_after** | Boolean value |
|**title** | A single |
|**order** |  |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID                | the share id |
|$SHARE_KEY         | the share key |
|$DOCUMENT_ID       | list documents |
|$CUSTOMER_ID       | the customer id |
|$GROUPE_ID         | the group id |
|$DATE_END_SHARE    | datetime |
|$DAYS_REMAINING    | number of days |
|$DATE_LAST_DOWNLOAD| datetime |
|$DELETE_FILE_AFTER | true or false |
|$TITLE             | the title |
|$DESCRIPTION       |  |
|$CHAPO             |  |
|$POSTSCRIPTUM      |  |


[documents-sharing-document]
### Input arguments

|Argument |Description |
|---      |--- |
|**id**         |  A single or a list of ids. |
|**file**       | A single share key |
|**file_key**   | A single or a list of document ids. |
|**title**      | A single |
|**order**      |  |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID                | the share id |
|$FILE              | the file |
|$FILE_KEY          | the file key |
|$TITLE             | the title |
|$DESCRIPTION       |  |
|$CHAPO             |  |
|$POSTSCRIPTUM      |  |

[documents-sharing-groupe]
### Input arguments

|Argument |Description |
|---      |--- |
|**id** |  A single or a list of ids. |
|**title** | A single title |
|**order** |  |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID                | the share id |
|$TITLE             | the file |
|$CUSTOMER_ID       | the client list  |

