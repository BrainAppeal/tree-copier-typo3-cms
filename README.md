# tree-copier-typo3-cms
tree-copier-typo3-cms
This version works for TYPO3 4.5.0 - 6.2.99
If you look for other TYPO3 versions, click the "Download" tab to get a history of all extension versions.

* Copies the subpage structure of a given source page to a destination page, sets the copied pages to "show content of {original} page". Source and target are configurable in a back-end module.
* The contents of the pages are not copied (otherwise you wouldn't need an extension, right?).
* Example application: Some websites use the same language for different countries, but the contents for these countries differ slightly. So it may be desirable to use several page-trees and show much of the same content in all these trees, but not everything.
* Pages set to show content of another page are displayed with a different icon on TYPO3 4.7 and 6.x. It may be necessary to clear your cache and delete the files inside typo3temp/sprites.

TER
http://typo3.org/extensions/repository/view/braintreecopier
