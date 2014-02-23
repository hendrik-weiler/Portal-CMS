<div class="col-xs-12 vertical graycontainer globalmenu">
    <div class="description">
        <?php print __('nav.advanced') ?>
    </div>
    <div class="list padding15">
      <ul class="nav nav-tabs">
        <li><a href="<?php print Uri::create('admin/advanced') ?>"><?php print __('advanced.tabs.back') ?></a></li>
        <li class="active"><a href="<?php print Uri::create('admin/advanced/update') ?>">Data Import</a></li>
      </ul>

            <h3>Import Data from another PortalCMS Instance.</h3>

            <h5>Status:</h5>
            <div class="status"></div>

            <h5>Credentials:</h5>
            <div class="input">URL: <input type="text" id="url" value="http://portalcms:8888/"><p>Just type in here the base URL to the PortalCMS you want (e.g http://mysite.com/)</p></div>
            <div class="input">Username: <input type="text" id="username" value="admin"></div>
            <div class="input">Password: <input type="text" id="password" value="test"></div>
            <div class="input"><button class="button check">Check credentials</button></div>

    </div>
    </div>
</div>
<style>
  #import .input {
    margin-top: 10px;
  }
  #import .input input {
    width: 100%;
  }
</style>
<script>

  $('button.check').click(function(e) {
    var url = $('#url').val();
    $.ajax({
      url: (url + '/admin/advanced/import/check'),
      data : {
        'username' : $('#username').val(),
        'password' : $('#password').val()
      },
      cache: false,
      dataType: 'jsonp',
      beforeSend : function() {
        $('.status').append('Loading...');
      },
      success: function(result){
        $('.status').empty();

        if(result.status == 'FAIL') {
          $('.status').append('Login failed.');
          return;
        }

        var version = $('<div>').text('Version: ' + result.version);
        $('.status').append(version);

        $('.status').append($('<h5>').text('Accounts:'));
        $.each(result.accounts, function(key, account) {
          var row = $('<div>', {'style':'overflow:hidden;'});
          var col1 = $('<div>', {'style':'float:left;width:49%;'}).text(account.name);
          var col2 = $('<div>', {'style':'float:left;width:49%;'});
          var button = $('<button>', {'class':'button'}).text('Import');
          row.append(col1).append(col2);
          col2.append(button);
          $('.status').append(row);
        });

        $('.status').append($('<h5>').text('Website:'));
        $.each(result.languages, function(key, language) {
          var row = $('<div>', {'style':'overflow:hidden;'});
          var col1 = $('<div>', {'style':'float:left;width:49%;'}).html('<strong>Language:</strong> ' + language.name);
          var col2 = $('<div>', {'style':'float:left;width:49%;'});
          var button = $('<button>', {'class':'button'}).text('Import');
          row.append(col1).append(col2);
          col2.append(button);
          $('.status').append($('<hr>')).append(row);
          $('.status').append($('<h6>').text('Sites:'));
          $.each(result['site_' + language.name], function(key, site) {
            var row = $('<div>', {'style':'overflow:hidden;'});
            var col1 = $('<div>', {'style':'float:left;width:45%;padding-left:20px;'}).text(site.name);
            var col2 = $('<div>', {'style':'float:left;width:49%;'});
            var button = $('<button>', {'class':'button'}).text('Import');
            row.append(col1).append(col2);
            col2.append(button);
            $('.status').append(row);
          });
          $('.status').append($('<h6>').text('News:'));
          $.each(result['news_' + language.name], function(key, news) {
            var row = $('<div>', {'style':'overflow:hidden;'});
            var col1 = $('<div>', {'style':'float:left;width:45%;padding-left:20px;'}).text(news.name);
            var col2 = $('<div>', {'style':'float:left;width:49%;'});
            var button = $('<button>', {'class':'button'}).text('Import');
            row.append(col1).append(col2);
            col2.append(button);
            $('.status').append(row);
          });

          var row = $('<div>', {'style':'overflow:hidden;'});
          var col1 = $('<div>', {'style':'float:left;width:49%;'}).html('<h5>Taxgroups</h5>');
          var col2 = $('<div>', {'style':'float:left;width:49%;'});
          var button = $('<button>', {'class':'button'}).text('Import');
          row.append(col1).append(col2);
          col2.append(button);
          $('.status').append(row);

          $('.status').append($('<h6>').text('Article:'));
          $.each(result.articles, function(key, article) {
            var row = $('<div>', {'style':'overflow:hidden;'});
            var col1 = $('<div>', {'style':'float:left;width:45%;padding-left:20px;'}).text(article.name);
            var col2 = $('<div>', {'style':'float:left;width:49%;'});
            var button = $('<button>', {'class':'button'}).text('Import');
            row.append(col1).append(col2);
            col2.append(button);
            $('.status').append(row);
          });

        });
      }
    });
  });
</script>