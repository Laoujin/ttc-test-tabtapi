$(function() {
  function setClub(competition, clubId, e) {
    $('#Competition').val(competition);
    $('#Club').val(clubId);
    e.preventDefault();
  }

  $('#VttlClub').click(e => setClub('VTTL', 'OVL134', e));
  $('#SportaClub').click(e => setClub('Sporta', '4055', e));


  // Wrap the code inside the required <code> tag, when needed:
  jQuery('pre[class*="language-"], pre[class*="lang-"]').each(function() {
    if (1 !== jQuery(this).children('code').length) {
      jQuery(this).wrapInner('<code>');
    }
  });

  // Highlight code, when the page finished loading (using jQuery here)
  Prism.highlightAll();
});


function copyToClipboard() {
  const codeBlock = document.getElementById('codeBlock');
  const copyButton = document.querySelector('.copy-button');

  // Create a selection and copy the text
  const selection = window.getSelection();
  const range = document.createRange();
  range.selectNodeContents(codeBlock);
  selection.removeAllRanges();
  selection.addRange(range);

  try {
    document.execCommand('copy');
    // Visual feedback for the user
    copyButton.textContent = 'Copied!';
    copyButton.classList.add('copied');

    // Reset button after 2 seconds
    setTimeout(() => {
      copyButton.textContent = 'Copy';
      copyButton.classList.remove('copied');
    }, 2000);
  } catch (err) {
    console.error('Failed to copy text: ', err);
    copyButton.textContent = 'Failed!';
  }

  selection.removeAllRanges();
}
