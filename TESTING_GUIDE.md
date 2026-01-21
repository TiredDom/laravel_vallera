# Testing Guide - Admin Dashboard

## Prerequisites
✅ Database migrated
✅ Frontend assets built (`npm run build`)
✅ Laravel server running (`php artisan serve`)

## Test Steps

### 1. Login as Super Admin
- Navigate to: `http://127.0.0.1:8000/login`
- Email: `superadmin@vallera.com`
- Password: `1234567890`

### 2. Test Dashboard
- You should see the admin dashboard with:
  - 6 statistics cards (Revenue, Orders, Users, Pending, Avg Order Value, New Users)
  - 3 quick access cards (Order Management, Product Management, Audit Logs)
  - Revenue trend chart
  - Order status distribution
  - Recent orders widget
  - Recent users widget
  - User management table

### 3. Test Order Management
- Click "Order Management" card or navigate to `/admin/orders`
- Features to test:
  - View all orders with status badges
  - Search by order ID, customer name, or email
  - Filter by status (all, pending, processing, completed, cancelled)
  - Click on an order to expand and see items
  - Update order status (try changing pending → processing → completed)
  - Verify statistics update at the top

### 4. Test Product Management
- Click "Product Management" card or navigate to `/admin/products`
- Features to test:
  - View all 8 furniture products
  - Search products by name
  - Filter by category
  - Check stock levels (color-coded: red < 10, amber < 15, green ≥ 15)
  - View inventory statistics at the top

### 5. Test Audit Logs (Super Admin Only)
- Click "Audit Logs" card or navigate to `/admin/logs`
- Features to test:
  - View all system activities
  - Search logs by user, action, or description
  - See color-coded action badges
  - Check IP addresses and timestamps
  - Verify pagination works (if more than 50 logs)
  - Log entries should show:
    - User promotions/demotions
    - Order status changes
    - Order creation from checkout
    - Order cancellations

### 6. Test User Management (Super Admin Only)
- Scroll to the "User Management" table on the dashboard
- Features to test:
  - View all users with order counts
  - See role badges (Super Admin, Admin, User)
  - Try promoting a user with @vallera.com email to admin
  - Try demoting an admin back to user
  - Verify super admin account shows "Protected"
  - Check confirmation modals appear

### 7. Test Activity Logging
To verify logs are being created:

#### A. Promote/Demote User
1. Go to dashboard
2. Promote a @vallera.com user to admin
3. Go to `/admin/logs`
4. You should see "PROMOTE USER" entry

#### B. Update Order Status
1. Go to `/admin/orders`
2. Expand an order and change status
3. Go to `/admin/logs`
4. You should see "UPDATE ORDER STATUS" entry

#### C. Create Order (Checkout)
1. Logout and login as regular user
2. Add items to cart
3. Complete checkout with payment
4. Login as super admin
5. Check `/admin/logs`
6. You should see "CREATE ORDER" entry

#### D. Cancel Order
1. Login as regular user
2. Go to "My Orders" (`/orders`)
3. Cancel a pending/processing order
4. Login as super admin
5. Check `/admin/logs`
6. You should see "CANCEL ORDER" entry

### 8. Test Regular Admin (Not Super Admin)
1. Create a user with @vallera.com email
2. Login as super admin and promote them
3. Logout and login as the new admin
4. Verify they can:
   - Access `/admin/dashboard`
   - Access `/admin/orders`
   - Access `/admin/products`
5. Verify they CANNOT:
   - Access `/admin/logs` (should get 403 error)
   - See promote/demote buttons in user management
   - See audit logs card on dashboard (should show locked)

### 9. Test Regular User
1. Login as regular user (non-admin)
2. Verify they CANNOT access:
   - `/admin/dashboard` (should redirect)
   - `/admin/orders` (should redirect)
   - `/admin/products` (should redirect)
   - `/admin/logs` (should redirect)
3. Verify they CAN access:
   - Home page
   - Products page
   - Cart
   - Checkout
   - My Orders page

## Expected Results

### All Pages Should Have:
- ✅ Clean, modern design
- ✅ Responsive layout
- ✅ Smooth animations
- ✅ Proper error handling
- ✅ Loading states
- ✅ Toast notifications for success/error
- ✅ Consistent color scheme

### Statistics Should:
- ✅ Update in real-time after actions
- ✅ Show accurate counts
- ✅ Display formatted currency (₱)
- ✅ Show percentage changes where applicable

### Security Should:
- ✅ Prevent unauthorized access
- ✅ Log all admin actions
- ✅ Track IP addresses
- ✅ Protect super admin account
- ✅ Validate permissions

## Troubleshooting

### If orders don't show:
1. Make sure you have orders in the database
2. Try creating an order by checking out as a user

### If logs don't show:
1. Perform some actions (promote user, update order status)
2. Check database: `SELECT * FROM activity_logs;`

### If super admin can't login:
1. Check database: `SELECT * FROM users WHERE email = 'superadmin@vallera.com';`
2. Run seeder: `php artisan db:seed --class=AdminSeeder`

### If styles look wrong:
1. Clear cache: `php artisan cache:clear`
2. Rebuild assets: `npm run build`
3. Hard refresh browser (Ctrl + Shift + R)

## Success Indicators
✅ All pages load without errors
✅ All features work as expected
✅ Logs are created for all actions
✅ Permissions are enforced correctly
✅ UI is modern, professional, and responsive
✅ No console errors in browser DevTools
✅ No PHP errors in Laravel logs
