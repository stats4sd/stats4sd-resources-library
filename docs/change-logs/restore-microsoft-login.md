# Restore "login with Microsoft"

The admin login page offers Microsoft (Azure AD) login again. The Socialment plugin had been
unregistered from the panel in commit `8c0f644`; the packages, config and `connected_accounts`
table were left in place, so this change only reconnects them.

## What changed

- `app/Providers/Filament/AdminPanelProvider.php`: registers `SocialmentPlugin` with the `azure`
  provider. A first-time Microsoft login signs in as the existing user with the same email
  address, or creates a new user with the `editor` role. Existing users keep their role.
  The plugin can only draw its button below the email form, so its own output is switched off
  (`->visible(false)`) and a `panels::auth.login.form.before` render hook draws the button, and
  any Microsoft login error, above the form instead.
- `resources/views/filament/socialment/providers-list.blade.php`: the button, then an "Or login
  with email:" divider, then (below it) the email form. The icon is drawn with `svg()` because
  the panel's `DisableBladeIconComponents` middleware switches `<x-icon>` off.
- `tests/Feature/Auth/MicrosoftLoginTest.php`: the button shows on the login page; a new
  Microsoft user becomes an editor; an existing user logs in and keeps their role.

## Setup needed per environment

Set these in `.env` (read by `config/services.php`):

```
AZURE_CLIENT_ID=
AZURE_CLIENT_SECRET=
AZURE_TENANT_ID=
AZURE_REDIRECT_URI=https://<site address>/login/azure/callback
```

The same redirect address must be added to the app registration in Azure.

`AZURE_TENANT_ID` must be the organisation's own tenant, not `common`: anyone who can log in
through that tenant becomes an editor, and a Microsoft account is matched to an existing user by
email address alone.

## Not verified

A real login against Azure has not been tested; the tests use a stand-in Microsoft account.
