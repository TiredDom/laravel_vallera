s# 📸 Image Guide for Vallera Furniture Website

## ✅ What You've Done So Far:
- ✅ Created `/public/images/` folder
- ✅ Updated Landing page hero section to use images
- ✅ Added error handling (image won't break site if missing)

---

## 🎯 Images to Download & Where to Place Them

### **Priority 1: MUST HAVE (Essential)**

#### 1️⃣ **Hero Section Image** (Landing Page)
**Search keywords:**
- "modern furniture living room interior"
- "luxury sofa showcase"
- "contemporary furniture design"
- "scandinavian living room"

**Specifications:**
- **Size:** 1920x1080px or larger (landscape)
- **Format:** JPG or WebP
- **Style:** Bright, professional, modern furniture in nice interior
- **File name:** `hero-furniture.jpg`
- **Location:** `public/images/hero-furniture.jpg`

**Where it appears:** Landing page hero section (right side)

---

### **Priority 2: RECOMMENDED (Improves site)**

#### 2️⃣ **About Page - Workshop/Craftsmanship**
**Search keywords:**
- "furniture workshop craftsman"
- "woodworking artisan"
- "furniture manufacturing"

**Specifications:**
- **Size:** 1200x800px
- **File names:** 
  - `about-workshop.jpg`
  - `about-craftsmanship.jpg`
- **Location:** `public/images/`

**Where it appears:** About page sections

---

#### 3️⃣ **Category Showcase Images** (Optional)
If you want better category displays:

**Sofas:**
- Search: "modern sofa product photography"
- File: `category-sofas.jpg`

**Tables:**
- Search: "dining table product photography"
- File: `category-tables.jpg`

**Chairs:**
- Search: "modern chair product photography"
- File: `category-chairs.jpg`

**Location:** `public/images/categories/`

---

### **Priority 3: OPTIONAL (Nice to have)**

#### 4️⃣ **Testimonial/Customer Images**
**Search:** "happy customer home interior"
- File: `customer-1.jpg`, `customer-2.jpg`, etc.
- Location: `public/images/testimonials/`

#### 5️⃣ **Logo/Branding**
- **Company Logo:** `logo.png` or `logo.svg`
- **Favicon:** Already exists at `public/favicon.ico`
- Location: `public/images/`

---

## 📁 Complete Folder Structure

```
public/
├── images/
│   ├── hero-furniture.jpg           ✅ Main hero image
│   ├── about-workshop.jpg           📝 About page
│   ├── about-craftsmanship.jpg      📝 About page
│   ├── logo.png                     📝 Optional logo
│   ├── categories/                  📝 Optional
│   │   ├── category-sofas.jpg
│   │   ├── category-tables.jpg
│   │   └── category-chairs.jpg
│   └── testimonials/                📝 Optional
│       ├── customer-1.jpg
│       └── customer-2.jpg
├── storage/                         ✅ Product images (uploaded by admin)
│   └── products/
│       └── product-*.jpg
├── grid.svg                         ✅ Already exists
└── favicon.ico                      ✅ Already exists
```

---

## 🔍 Where to Find Good Images

### **Free Stock Photo Sites:**

1. **Unsplash** (https://unsplash.com/)
   - Search: "furniture", "interior design", "modern living room"
   - ✅ Free commercial use
   - ✅ High quality
   - ✅ No attribution required

2. **Pexels** (https://pexels.com/)
   - Search: "furniture", "home decor", "sofa"
   - ✅ Free commercial use
   - ✅ Good variety

3. **Pixabay** (https://pixabay.com/)
   - Search: "furniture", "interior"
   - ✅ Free for commercial use

### **Paid Options (Better Quality):**

1. **Shutterstock** (https://shutterstock.com/)
   - Professional furniture photography
   - High resolution

2. **Adobe Stock** (https://stock.adobe.com/)
   - Premium quality
   - Furniture-specific collections

---

## 🎨 Image Requirements & Best Practices

### **Technical Specs:**

| Image Type | Recommended Size | Format | Max File Size |
|-----------|-----------------|--------|---------------|
| Hero Image | 1920x1080px | JPG/WebP | 500KB |
| Category | 800x600px | JPG | 200KB |
| About Page | 1200x800px | JPG | 300KB |
| Logo | 500x500px | PNG/SVG | 100KB |
| Products | 800x800px | JPG | 200KB |

### **Quality Guidelines:**

✅ **DO:**
- Use bright, well-lit images
- Choose professional photography
- Keep consistent style (modern/minimalist)
- Compress images before uploading
- Use landscape orientation for hero images

❌ **DON'T:**
- Use watermarked images
- Mix different photography styles
- Use low-resolution images
- Upload huge file sizes (slows website)

---

## 💻 How to Add Images

### **Step 1: Download Images**
1. Go to Unsplash/Pexels
2. Search for furniture images
3. Download in high resolution
4. Rename to match the guide

### **Step 2: Place in Correct Folder**
```
Copy files to:
C:\Users\User\Desktop\School\assignment_webdev\public\images\
```

### **Step 3: Verify Images Load**
1. Visit: `http://127.0.0.1:8000`
2. Check if hero image appears
3. If not, check browser console for errors

---

## 🔧 Already Updated Code

### **Landing Page Hero Section** ✅
```vue
<img 
    src="/images/hero-furniture.jpg" 
    alt="Premium Furniture Showcase"
    class="w-full h-full object-cover"
    @error="$event.target.style.display='none'"
/>
```

**Features:**
- ✅ Loads image from `/public/images/`
- ✅ Graceful fallback if image missing
- ✅ Proper sizing and object-fit
- ✅ Gradient overlay for better text readability

---

## 📝 Quick Action Checklist

**Right Now:**
- [ ] Download 1 hero image from Unsplash
- [ ] Rename it to `hero-furniture.jpg`
- [ ] Place in `public/images/` folder
- [ ] Refresh website to see it!

**Later (Optional):**
- [ ] Download about page images
- [ ] Add category showcase images
- [ ] Create product images via admin panel
- [ ] Add logo if needed

---

## 🎯 Recommended Search Terms by Category

### **For Hero Image:**
- "modern scandinavian living room"
- "luxury furniture interior design"
- "contemporary sofa showcase"
- "minimalist home decor"

### **For Furniture Products:**
- "modern sofa white background"
- "dining table product shot"
- "office chair isolated"
- "bookshelf furniture photography"

### **For About/Workshop:**
- "furniture craftsman working"
- "woodworking workshop"
- "artisan furniture maker"
- "furniture manufacturing"

---

## ⚡ Quick Test

After adding `hero-furniture.jpg`:

1. Visit: `http://127.0.0.1:8000`
2. Check hero section on right side
3. Image should display beautifully!
4. If not showing, check:
   - File name is exactly: `hero-furniture.jpg`
   - Located in: `public/images/`
   - File size < 2MB
   - Valid image format (JPG/PNG/WebP)

---

## 🎨 Image Optimization Tips

**Before uploading:**
1. Resize to recommended dimensions
2. Compress using:
   - TinyPNG (https://tinypng.com/)
   - Squoosh (https://squoosh.app/)
   - ImageOptim (Mac)

**Benefits:**
- ✅ Faster page load
- ✅ Better performance
- ✅ Less bandwidth usage

---

## 📊 Priority Order

**Day 1 (NOW):**
1. ✅ Hero furniture image - **ESSENTIAL**

**Day 2:**
2. About page images - Recommended
3. Logo (if you want custom branding)

**Day 3+:**
4. Category showcase images - Optional
5. Testimonial images - Optional

**Ongoing:**
- Product images uploaded via admin panel

---

## ✅ Current Status

**What's Ready:**
- ✅ Images folder created
- ✅ Landing page updated to use images
- ✅ Error handling added
- ✅ Product images system working (uploaded via admin)

**What You Need to Do:**
- 📥 Download 1 hero image from Unsplash
- 📁 Place in `public/images/hero-furniture.jpg`
- 🎉 Refresh website to see it!

---

## 🚀 Quick Start (5 Minutes)

1. **Open** https://unsplash.com/
2. **Search** "modern furniture living room"
3. **Download** a beautiful image
4. **Rename** to `hero-furniture.jpg`
5. **Copy** to `C:\Users\User\Desktop\School\assignment_webdev\public\images\`
6. **Refresh** `http://127.0.0.1:8000`
7. **Enjoy** your beautiful hero section! 🎉

---

## 📞 Need Help?

**Common Issues:**

**Image not showing?**
- Check file path: `public/images/hero-furniture.jpg`
- Check file name spelling (case-sensitive)
- Check file format (JPG/PNG/WebP)
- Hard refresh browser (Ctrl + Shift + R)

**Image looks stretched?**
- Image should be landscape (wider than tall)
- Recommended: 1920x1080 or 1600x900

**Page loads slow?**
- Compress image (use TinyPNG)
- Target file size: < 500KB

---

**Status: Ready to add images! Just download, place, and refresh!** 🎨✨
