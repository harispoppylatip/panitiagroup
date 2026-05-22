# Controller Reorganization Mapping

## Current → Recommended Structure

### CURRENT STATE

```
app/Http/Controllers/
├── AdminAuthController.php
├── AdminController.php
├── AdminFinanceController.php
├── AdminUserController.php
├── BarcodeController.php
├── BerandaController.php
├── Controller.php
├── GrubkasController.php
├── PaymentVerificationController.php
├── TugasController.php
├── ambiljadwalapi.php                 ⚠️ Naming Issue
├── controllerpost.php                 ⚠️ Naming Issue
├── controllerscanner.php              ⚠️ Naming Issue
├── indexcontroller.php                ⚠️ Naming Issue
├── tokencontroller.php                ⚠️ Naming Issue
└── test.php                           ⚠️ Naming Issue
```

### TARGET STATE

```
app/Http/Controllers/
├── Controller.php                     [Base Class - Keep]
├── Auth/
│   ├── AdminAuthController.php        [Moved from root]
│   └── ScannerAuthController.php      [Renamed from controllerscanner.php]
├── Admin/
│   ├── AdminUserController.php        [Moved from root - unchanged]
│   ├── SettingsController.php         [Extracted from AdminController]
│   └── CalibrationController.php      [Extracted from AdminFinanceController - high-risk ops]
├── Finance/
│   ├── FinanceDashboardController.php [Extracted from AdminFinanceController - index method]
│   ├── FinanceSettingsController.php  [Extracted from AdminFinanceController]
│   ├── MemberBalanceController.php    [Extracted from AdminFinanceController]
│   ├── ExpenseController.php          [Extracted from AdminFinanceController]
│   └── PaymentVerificationController.php [Moved from root]
├── Finance/
│   ├── GrubkasController.php          [Moved from root - web methods only]
│   ├── PaymentController.php          [Extracted from GrubkasController - bayar method]
│   └── SendFundsController.php        [Extracted from GrubkasController]
├── Content/
│   ├── BerandaController.php          [Moved from root - hero images only]
│   ├── TeamController.php             [Extracted from BerandaController]
│   ├── PostController.php             [Renamed from controllerpost.php]
│   └── ImageController.php            [Renamed from indexcontroller.php]
├── Academic/
│   ├── TaskController.php             [Renamed from TugasController - web methods only]
│   ├── ScheduleController.php         [Renamed from ambiljadwalapi.php]
│   └── AttendanceController.php       [Renamed from BarcodeController]
├── Member/
│   └── TokenController.php            [Renamed from tokencontroller.php - web methods]
├── Api/
│   ├── TaskApiController.php          [Extracted from TugasController - API methods]
│   ├── GrubkasApiController.php       [Extracted from GrubkasController - grubkasinfoapi]
│   └── TokenApiController.php         [Extracted from TokenController - API methods]
└── Debug/
    └── TestController.php             [Renamed from test.php] ⚠️ Remove before production
```

---

## Detailed File Migration Plan

### Phase 1: Renaming (SAFE - No Logic Changes)

| Current File          | New File                  | Folder    | Status              |
| --------------------- | ------------------------- | --------- | ------------------- |
| ambiljadwalapi.php    | ScheduleController.php    | Academic/ | Rename class + file |
| controllerpost.php    | PostController.php        | Content/  | Rename class + file |
| controllerscanner.php | ScannerAuthController.php | Auth/     | Rename class + file |
| indexcontroller.php   | ImageController.php       | Content/  | Rename class + file |
| tokencontroller.php   | TokenController.php       | Member/   | Rename class + file |
| test.php              | TestController.php        | Debug/    | Rename class + file |
| BarcodeController.php | AttendanceController.php  | Academic/ | Rename file         |

---

### Phase 2: Controller Splitting

#### **AdminFinanceController.php** → 5 New Controllers

**FinanceDashboardController.php** (Finance/FinanceDashboardController.php)

```php
namespace App\Http\Controllers\Finance;

class FinanceDashboardController extends Controller
{
    public function index()  // Full implementation from AdminFinanceController::index
}
```

**FinanceSettingsController.php** (Finance/FinanceSettingsController.php)

```php
namespace App\Http\Controllers\Finance;

class FinanceSettingsController extends Controller
{
    public function updateWeeklyFee(Request $request)
}
```

**MemberBalanceController.php** (Finance/MemberBalanceController.php)

```php
namespace App\Http\Controllers\Finance;

class MemberBalanceController extends Controller
{
    public function updateMemberBalance(Request $request, string $nim)
}
```

**ExpenseController.php** (Finance/ExpenseController.php)

```php
namespace App\Http\Controllers\Finance;

class ExpenseController extends Controller
{
    public function storeExpense(Request $request)
    public function storeCashAdjustment(Request $request)
}
```

**CalibrationController.php** (Admin/CalibrationController.php)

```php
namespace App\Http\Controllers\Admin;

class CalibrationController extends Controller
{
    public function showDataCalibrationForm()
    public function executeDataCalibration(Request $request)
}
```

#### **BerandaController.php** → 2 New Controllers

**TeamController.php** (Content/TeamController.php)

```php
namespace App\Http\Controllers\Content;

class TeamController extends Controller
{
    public function editTeam()
    public function storeTeam(Request $request)
    public function updateTeam(Request $request, TeamMember $teamMember)
    public function destroyTeam(TeamMember $teamMember)

    // Private helper
    private function processImage($request, $fieldName, $oldImageUrl = null)
}
```

**BerandaController.php** (Content/BerandaController.php) - Kept but reduced

```php
namespace App\Http\Controllers\Content;

class BerandaController extends Controller
{
    public function index()
    public function editHero()
    public function updateHero(Request $request)

    // Private helper
    private function processImage($request, $fieldName, $oldImageUrl = null)
}
```

#### **GrubkasController.php** → 3 New Controllers

**GrubkasController.php** (Finance/GrubkasController.php) - Kept but reduced

```php
namespace App\Http\Controllers\Finance;

class GrubkasController extends Controller
{
    public function index()
    public function grubkasinfoapi()

    private function getGrubkasData()
}
```

**PaymentController.php** (Finance/PaymentController.php)

```php
namespace App\Http\Controllers\Finance;

class PaymentController extends Controller
{
    public function bayar(Request $request)
}
```

**SendFundsController.php** (Finance/SendFundsController.php)

```php
namespace App\Http\Controllers\Finance;

class SendFundsController extends Controller
{
    public function sendFundsInitiate(Request $request)
}
```

#### **AdminController.php** → SettingsController

**SettingsController.php** (Admin/SettingsController.php)

```php
namespace App\Http\Controllers\Admin;

class SettingsController extends Controller
{
    public function inserttoken()
    public function membertoken()
    public function scanLoginSetting()
    public function updateScanLoginSetting(Request $request)
}
```

#### **TugasController.php** → Split (Keep original, extract API)

**TaskController.php** (Academic/TaskController.php) - Web methods only

```php
namespace App\Http\Controllers\Academic;

class TaskController extends Controller
{
    public function front(Request $request)
    public function index()
    public function create()
    public function postnew(Request $request)
    public function show(int $id)
    public function edit(int $id)
    public function update(Request $request, $id)
    public function destroy($id)

    // REMOVE: dummyTugas() and getTugasById()
}
```

#### **TokenController.php** → Split (Keep web, extract API)

**TokenController.php** (Member/TokenController.php) - Web methods only

```php
namespace App\Http\Controllers\Member;

class TokenController extends Controller
{
    public function index()
    public function membertokenproses(Request $request)
    public function destroy($id)
    public function edit($id)
    public function update(Request $request, $id)
    public function refreshAllTokens()

    // MOVE: listUsers(), updateUserStatus(), refreshtoken() to TokenApiController
}
```

---

### Phase 3: API Controller Creation

**TaskApiController.php** (Api/TaskApiController.php)

```php
namespace App\Http\Controllers\Api;

class TaskApiController extends Controller
{
    public function storeapi(Request $request)
    public function gettugasapi()
    public function deletetugasapi(Request $request)
    public function edittugasapi(Request $request)
}
```

**GrubkasApiController.php** (Api/GrubkasApiController.php)

```php
namespace App\Http\Controllers\Api;

class GrubkasApiController extends Controller
{
    public function grubkasinfoapi()  // Moved from GrubkasController
}
```

**TokenApiController.php** (Api/TokenApiController.php)

```php
namespace App\Http\Controllers\Api;

class TokenApiController extends Controller
{
    public function listUsers()       // From TokenController
    public function updateUserStatus(Request $request, $id)  // From TokenController
    public function refreshtoken()    // From TokenController
}
```

---

### Phase 4: No Changes (Moved only)

| File                              | New Location                              | Reason             |
| --------------------------------- | ----------------------------------------- | ------------------ |
| AdminAuthController.php           | Auth/AdminAuthController.php              | Organize by domain |
| AdminUserController.php           | Admin/AdminUserController.php             | Organize by domain |
| PaymentVerificationController.php | Finance/PaymentVerificationController.php | Organize by domain |

---

## Route Updates Required

### Current Routes (examples)

```php
// routes/web.php
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::get('/admin/inserttoken', [AdminController::class, 'inserttoken']);
Route::get('/admin/finance', [AdminFinanceController::class, 'index']);
Route::get('/grubkas', [GrubkasController::class, 'index']);

// routes/api.php
Route::post('/tugas', [TugasController::class, 'storeapi']);
Route::get('/grubkas/info', [GrubkasController::class, 'grubkasinfoapi']);
```

### Updated Routes (after reorganization)

```php
// routes/web.php
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::get('/admin/settings/token', [SettingsController::class, 'inserttoken']);
Route::get('/admin/finance', [FinanceDashboardController::class, 'index']);
Route::get('/grubkas', [GrubkasController::class, 'index']);

// routes/api.php
Route::post('/tugas', [TaskApiController::class, 'storeapi']);
Route::get('/grubkas/info', [GrubkasApiController::class, 'grubkasinfoapi']);
Route::post('/tokens', [TokenApiController::class, 'store']);
```

---

## Namespace Mapping

### Before

```
All controllers directly in App\Http\Controllers namespace
```

### After

```
App\Http\Controllers\Controller (base)
App\Http\Controllers\Auth\AdminAuthController
App\Http\Controllers\Auth\ScannerAuthController
App\Http\Controllers\Admin\AdminUserController
App\Http\Controllers\Admin\SettingsController
App\Http\Controllers\Admin\CalibrationController
App\Http\Controllers\Finance\FinanceDashboardController
App\Http\Controllers\Finance\FinanceSettingsController
App\Http\Controllers\Finance\MemberBalanceController
App\Http\Controllers\Finance\ExpenseController
App\Http\Controllers\Finance\PaymentVerificationController
App\Http\Controllers\Finance\GrubkasController
App\Http\Controllers\Finance\PaymentController
App\Http\Controllers\Finance\SendFundsController
App\Http\Controllers\Content\BerandaController
App\Http\Controllers\Content\TeamController
App\Http\Controllers\Content\PostController
App\Http\Controllers\Content\ImageController
App\Http\Controllers\Academic\TaskController
App\Http\Controllers\Academic\ScheduleController
App\Http\Controllers\Academic\AttendanceController
App\Http\Controllers\Member\TokenController
App\Http\Controllers\Api\TaskApiController
App\Http\Controllers\Api\GrubkasApiController
App\Http\Controllers\Api\TokenApiController
App\Http\Controllers\Debug\TestController (remove before production)
```

---

## File Movement Timeline

### Day 1 - Preparation

- [ ] Create backup of Controllers folder
- [ ] Create folder structure in app/Http/Controllers
- [ ] Review this document with team

### Day 2 - Phase 1 (Renaming)

- [ ] Rename 6 files with naming issues
- [ ] Update all Route references
- [ ] Run tests

### Day 3 - Phase 2 (Splitting AdminFinanceController)

- [ ] Create 5 new controllers in Finance/ and Admin/
- [ ] Move methods accordingly
- [ ] Update routes/web.php
- [ ] Run tests

### Day 4 - Phase 2 (Splitting BerandaController & GrubkasController)

- [ ] Create TeamController
- [ ] Create PaymentController and SendFundsController
- [ ] Move methods accordingly
- [ ] Update routes
- [ ] Run tests

### Day 5 - Phase 3 (API Controllers)

- [ ] Create Api/ folder with 3 new API controllers
- [ ] Move API methods from web controllers
- [ ] Update routes/api.php
- [ ] Run tests

### Day 6 - Phase 4 (Cleanup)

- [ ] Delete original split controllers
- [ ] Remove test.php before production
- [ ] Remove dummy methods from TaskController
- [ ] Final cleanup pass

### Day 7 - Testing & Documentation

- [ ] Full integration testing
- [ ] Update project documentation
- [ ] Brief team on new structure
- [ ] Deploy to production

---

## Validation Checklist

### Before Starting

- [ ] All controllers backed up
- [ ] Team informed of changes
- [ ] Tests exist for all controllers

### After Phase 1

- [ ] All 6 files renamed successfully
- [ ] Classes renamed in files
- [ ] All routes updated and working
- [ ] Tests passing

### After Phase 2

- [ ] 5 new controllers created
- [ ] Methods moved completely
- [ ] No duplicate methods
- [ ] Routes functional
- [ ] Tests passing

### After Phase 3

- [ ] 3 API controllers created
- [ ] API routes updated
- [ ] Web routes still functional
- [ ] API endpoints working
- [ ] Tests passing

### Before Production

- [ ] TestController removed (Debug/ folder deleted)
- [ ] Dummy methods removed (TugasController)
- [ ] All tests passing
- [ ] No naming violations remaining
- [ ] Documentation updated
- [ ] Team trained on new structure

---

## Migration Script Helper

```bash
# Create folder structure
mkdir -p app/Http/Controllers/Auth
mkdir -p app/Http/Controllers/Admin
mkdir -p app/Http/Controllers/Finance
mkdir -p app/Http/Controllers/Content
mkdir -p app/Http/Controllers/Academic
mkdir -p app/Http/Controllers/Member
mkdir -p app/Http/Controllers/Api
mkdir -p app/Http/Controllers/Debug

# Backup original
cp -r app/Http/Controllers app/Http/Controllers.backup

# After manual file edits and moves:
# Find and replace in all files
# grep -r "AdminFinanceController" app/Http
# grep -r "use App\\Http\\Controllers;" app/Http
```

---

## Common Issues & Solutions

### Issue 1: Route Still References Old Controller

**Solution:** Search entire project for old controller name and update routes

### Issue 2: Import Statements Need Updating

**Solution:** Use find & replace on namespace paths

```php
// Before
use App\Http\Controllers\AdminFinanceController;
// After
use App\Http\Controllers\Finance\FinanceDashboardController;
```

### Issue 3: Tests Reference Old Controllers

**Solution:** Update test namespace imports and mock references

### Issue 4: ServiceProviders Reference Controllers

**Solution:** Check AppServiceProvider and other providers for hard-coded controller references

---

## Expected Outcome

✅ **Improved Code Organization**

- Controllers grouped by domain/feature
- Easier to find functionality
- Better separation of concerns

✅ **Better Maintainability**

- Clear controller responsibilities
- Reduced method count per controller
- Logical namespace hierarchy

✅ **PSR-12 Compliance**

- All controllers follow naming standards
- Consistent structure across project
- Professional codebase

✅ **Scalability**

- Easy to add new features in proper folders
- New developers understand structure quickly
- Less code conflicts in team development
