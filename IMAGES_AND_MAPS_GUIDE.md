# 📸 Static Images & Google Maps - Complete Guide

## ✅ GOOGLE MAPS - DONE!

**Location:** Polytechnic University of the Philippines - San Pedro Campus
**Status:** ✅ Embedded and working!

The Contact page now shows an **interactive Google Maps** pointing to:
📍 **Polytechnic University of the Philippines – San Pedro Campus**
📍 **San Pedro City, Laguna**

Visit `/contact` to see the map! Users can:
- View the location
- Get directions
- Zoom in/out
- Switch to satellite view
- Open in Google Maps app

---

## 📸 STATIC IMAGES PLAN

### **Priority 1: MUST HAVE** 🔥

#### **Hero Image (Landing Page)**
**What:** Main hero section image on homepage
**File name:** `hero-furniture.jpg`
**Location:** `public/images/hero-furniture.jpg`

**Where to search:**
1. Go to: **https://unsplash.com/**
2. Search: `"modern furniture living room"`
3. Alternative searches:
   - "luxury sofa interior design"
   - "contemporary living room furniture"
   - "scandinavian furniture interior"
   - "modern home decor"

**Specifications:**
- **Size:** 1920x1080px (landscape) or larger
- **Format:** JPG or WebP
- **Style:** Bright, professional, modern furniture showcase
- **File size:** < 500KB (compress if needed)

**Quick Steps:**
```
1. Visit unsplash.com
2. Search "modern furniture living room"
3. Pick a beautiful image
4. Download (Large size)
5. Rename to: hero-furniture.jpg
6. Place in: C:\Users\User\Desktop\School\assignment_webdev\public\images\
7. Refresh website at http://127.0.0.1:8000
```

---

### **Priority 2: OPTIONAL (But Nice!)** ⭐

#### **About Page Images**
**What:** Workshop/craftsmanship images
**File names:** 
- `about-workshop.jpg`
- `about-craftsmanship.jpg`
**Location:** `public/images/`

**Where to search:**
- Search: "furniture workshop craftsman"
- Search: "woodworking artisan"
- Search: "furniture manufacturing"

**Specifications:**
- Size: 1200x800px
- Format: JPG
- File size: < 300KB each

---

### **Priority 3: PRODUCT IMAGES** 📦

**Note:** Product images are uploaded through the **Admin Panel**, not manually!

**How to add product images:**
1. Login as admin/superadmin
2. Go to Admin Dashboard → Products
3. Click "Add Product" or "Edit Product"
4. Upload image through the form
5. Images automatically saved to `storage/app/public/products/`

**No need to manually place product images in public folder!**

---

## 📁 FOLDER STRUCTURE

```
public/
├── images/                          ← Create this folder ✅ (Already created!)
│   ├── hero-furniture.jpg          ← Place your hero image here
│   ├── about-workshop.jpg          ← Optional
│   └── about-craftsmanship.jpg     ← Optional
│
├── storage/                         ← Automatically managed by Laravel
│   └── products/                    ← Product images (uploaded via admin)
│       └── product-*.jpg
│
├── grid.svg                         ← ✅ Already exists (texture pattern)
└── favicon.ico                      ← ✅ Already exists (browser icon)
```

---

## 🔍 WHERE TO FIND FREE IMAGES

### **1. Unsplash.com** ⭐ RECOMMENDED
**URL:** https://unsplash.com/
**Pros:**
- ✅ Completely free
- ✅ High quality
- ✅ Commercial use allowed
- ✅ No attribution required
- ✅ Huge variety

**Best searches for furniture:**
- "modern furniture living room"
- "luxury sofa interior"
- "contemporary home decor"
- "scandinavian interior design"

---

### **2. Pexels.com**
**URL:** https://pexels.com/
**Pros:**
- ✅ Free for commercial use
- ✅ Good variety
- ✅ No attribution required

**Best searches:**
- "furniture showroom"
- "modern sofa"
- "interior design"

---

### **3. Pixabay.com**
**URL:** https://pixabay.com/
**Pros:**
- ✅ Free for commercial use
- ✅ Large collection

**Best searches:**
- "furniture"
- "living room"
- "home interior"

---

## 📝 QUICK CHECKLIST

### **For Hero Image:**
- [ ] Visit Unsplash.com
- [ ] Search "modern furniture living room"
- [ ] Download a beautiful image
- [ ] Rename to `hero-furniture.jpg`
- [ ] Place in `public/images/` folder
- [ ] Visit http://127.0.0.1:8000 to see it!

### **For About Images (Optional):**
- [ ] Search "furniture workshop"
- [ ] Download 1-2 images
- [ ] Rename appropriately
- [ ] Place in `public/images/` folder

### **For Product Images:**
- [ ] Use Admin Panel → Products
- [ ] Upload via "Add Product" form
- [ ] System handles storage automatically

---

## 🎨 IMAGE REQUIREMENTS

### **Hero Image:**
| Aspect | Requirement |
|--------|-------------|
| Size | 1920x1080px (landscape) |
| Format | JPG or WebP |
| Max file size | 500KB |
| Style | Bright, professional, modern |
| Orientation | Landscape (wider than tall) |

### **About Images:**
| Aspect | Requirement |
|--------|-------------|
| Size | 1200x800px |
| Format | JPG |
| Max file size | 300KB each |
| Style | Workshop/craftsmanship |

### **Product Images:**
| Aspect | Requirement |
|--------|-------------|
| Size | 800x800px (square) |
| Format | JPG or PNG |
| Max file size | 200KB |
| Background | White or transparent |

---

## 🛠️ IMAGE OPTIMIZATION TIPS

**Before uploading, compress your images:**

1. **TinyPNG** - https://tinypng.com/
   - Drag & drop
   - Download compressed version

2. **Squoosh** - https://squoosh.app/
   - Browser-based
   - Visual quality comparison

3. **ImageOptim** (Mac only)
   - Desktop app
   - Batch processing

**Benefits of compression:**
- ✅ Faster page load
- ✅ Better performance
- ✅ Less bandwidth
- ✅ Better SEO

---

## 🌐 GOOGLE MAPS - DETAILS

### **What Was Added:**

**Before:**
```vue
<!-- Placeholder with icon -->
<div class="bg-gradient-to-br from-zinc-200 to-zinc-300">
    <MapPinIcon />
    <p>Interactive Map</p>
</div>
```

**After:**
```vue
<!-- Real Google Maps embed -->
<iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!..."
    width="100%" 
    height="100%" 
    allowfullscreen="" 
    loading="lazy"
></iframe>
```

### **Map Features:**
- ✅ Interactive zoom/pan
- ✅ Get directions button
- ✅ Street view available
- ✅ Satellite view toggle
- ✅ Mobile-responsive
- ✅ Loads quickly (lazy loading)

### **Location Details:**
📍 **Polytechnic University of the Philippines – San Pedro Campus**
📍 San Pedro City, Laguna
📍 Philippines

---

## 📞 CONTACT INFORMATION

**Current Contact Info on Website:**
- **Address:** PUP San Pedro Campus, San Pedro City, Laguna
- **Email:** info@vallera.com
- **Phone:** +63 912 345 6789
- **Hours:** 
  - Mon-Fri: 9am - 7pm
  - Sat-Sun: 10am - 5pm

---

## ✅ STATUS SUMMARY

### **Completed:**
✅ Google Maps embedded (PUP San Pedro Campus)
✅ Address updated to PUP San Pedro Campus
✅ Image folder structure created
✅ Image guide documentation complete

### **To Do (Quick - 5 minutes):**
📥 Download 1 hero image from Unsplash
📁 Place in `public/images/hero-furniture.jpg`
🎉 Done!

---

## 🚀 TESTING

### **Test Google Maps:**
1. Visit: http://127.0.0.1:8000/contact
2. Scroll to "Visit Our Showroom" section
3. ✅ Map should be visible and interactive
4. ✅ Can zoom in/out
5. ✅ Can get directions
6. ✅ Shows PUP San Pedro Campus

### **Test Hero Image (After adding):**
1. Download and place `hero-furniture.jpg` in `public/images/`
2. Visit: http://127.0.0.1:8000
3. ✅ Hero image should display on right side
4. ✅ Image should look professional
5. ✅ No broken image icon

---

## 🎯 RECOMMENDED IMAGE SEARCHES

### **For Hero Section:**
1. "modern scandinavian living room"
2. "luxury furniture interior design"
3. "contemporary sofa showcase"
4. "minimalist home furniture"
5. "modern living room with sofa"

### **For About Page:**
1. "furniture craftsman working"
2. "woodworking workshop interior"
3. "furniture manufacturing"
4. "artisan making furniture"
5. "carpenter workshop"

---

## 💡 PRO TIPS

**Image Selection:**
- ✅ Choose bright, well-lit images
- ✅ Avoid watermarked images
- ✅ Match your brand colors (green/primary)
- ✅ Show furniture clearly
- ✅ Professional photography only

**File Naming:**
- ✅ Use lowercase
- ✅ Use hyphens, not spaces
- ✅ Be descriptive but short
- ✅ Example: `hero-furniture.jpg` ✅
- ❌ Bad: `Image 1.JPG` ❌

**Storage:**
- ✅ Static images → `public/images/`
- ✅ Product uploads → Admin panel (automatic)
- ✅ User avatars → Not implemented yet
- ✅ Compress before uploading

---

## 📊 CURRENT FILE STATUS

### **Existing Files:**
| File | Location | Status |
|------|----------|--------|
| grid.svg | public/ | ✅ Exists |
| favicon.ico | public/ | ✅ Exists |
| images/ folder | public/ | ✅ Created |

### **Needed Files:**
| File | Location | Priority |
|------|----------|----------|
| hero-furniture.jpg | public/images/ | 🔥 High |
| about-workshop.jpg | public/images/ | ⭐ Optional |

---

## 🎉 FINAL CHECKLIST

### **Before Finals:**
- [x] ✅ Google Maps working (PUP San Pedro Campus)
- [x] ✅ Address updated
- [x] ✅ Image folder created
- [ ] 📥 Hero image downloaded and placed
- [ ] 🎨 (Optional) About images added
- [x] ✅ Product images system working (Admin panel)

---

## 📞 QUICK HELP

**If hero image not showing:**
1. Check file name is exactly: `hero-furniture.jpg`
2. Check location is: `public/images/hero-furniture.jpg`
3. Hard refresh browser: `Ctrl + Shift + R`
4. Check file size < 2MB
5. Check file format is JPG/PNG/WebP

**If map not showing:**
1. Check internet connection
2. Try different browser
3. Disable ad-blocker
4. Hard refresh: `Ctrl + Shift + R`

---

## 🏆 READY FOR FINALS!

Your Vallera Furniture website now has:
- ✅ Professional Google Maps integration
- ✅ Clear image system
- ✅ Proper folder structure
- ✅ Complete documentation

**All you need to do:**
1. Download 1 hero image (5 minutes)
2. Place in correct folder
3. You're done! 🎉

**Website Location:** http://127.0.0.1:8000
**Contact Page Map:** http://127.0.0.1:8000/contact

---

Good luck with your finals! 🎓✨
