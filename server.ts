import express from "express";
import path from "path";
import fs from "fs";
import { createServer as createViteServer } from "vite";

async function startServer() {
  const app = express();
  const PORT = 3000;

  app.use(express.json({ limit: "50mb" }));
  app.use(express.urlencoded({ limit: "50mb", extended: true }));

  // Ensure data folder exists
  const dataDir = path.join(process.cwd(), "data");
  if (!fs.existsSync(dataDir)) {
    fs.mkdirSync(dataDir, { recursive: true });
  }

  const pricesFilePath = path.join(dataDir, "prices.json");
  const backupFilePath = path.join(dataDir, "backup.json");

  // Helper to read JSON safely
  const readJsonFileSync = (filePath: string, fallback: any) => {
    try {
      if (fs.existsSync(filePath)) {
        const raw = fs.readFileSync(filePath, "utf-8");
        return JSON.parse(raw);
      }
    } catch (e) {
      console.error(`Error reading file: ${filePath}`, e);
    }
    return fallback;
  };

  // Helper to write JSON safely
  const writeJsonFileSync = (filePath: string, data: any) => {
    try {
      fs.writeFileSync(filePath, JSON.stringify(data, null, 2), "utf-8");
      return true;
    } catch (e) {
      console.error(`Error writing file: ${filePath}`, e);
      return false;
    }
  };

  // Default prices configuration
  const defaultPrices = {
    goldPrice: 140000,
    oldGoldPriceGeneral: 138000,
    oldGoldPriceNakmachi: 138000,
    newSilverPrice: 2050,
    oldSilverPrice: 1700
  };

  // API Route - Get prices
  app.get("/api/prices", (req, res) => {
    const currentPrices = readJsonFileSync(pricesFilePath, defaultPrices);
    res.json(currentPrices);
  });

  // API Route - Save prices (receives partial updates)
  app.post("/api/save-price", (req, res) => {
    const currentPrices = readJsonFileSync(pricesFilePath, defaultPrices);
    const updated = { ...currentPrices, ...req.body };
    const success = writeJsonFileSync(pricesFilePath, updated);
    if (success) {
      res.json({ success: true, prices: updated });
    } else {
      res.status(500).json({ error: "Could not save price" });
    }
  });

  // API Route - Sync backup data (bills, loans, configurations)
  app.get("/api/sync", (req, res) => {
    const currentBackup = readJsonFileSync(backupFilePath, {
      bills: "[]",
      loans: "[]",
      settings: "{}"
    });
    res.json(currentBackup);
  });

  app.post("/api/sync", (req, res) => {
    const backupData = {
      bills: req.body.bills || "[]",
      loans: req.body.loans || "[]",
      settings: req.body.settings || "{}"
    };
    const success = writeJsonFileSync(backupFilePath, backupData);
    if (success) {
      res.json({ success: true, message: "Sync successful" });
    } else {
      res.status(500).json({ error: "Could not sync backup data" });
    }
  });

  // Vite development integration
  if (process.env.NODE_ENV !== "production") {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: "spa",
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), "dist");
    app.use(express.static(distPath));
    app.get("*", (req, res) => {
      res.sendFile(path.join(distPath, "index.html"));
    });
  }

  app.listen(PORT, "0.0.0.0", () => {
    console.log(`Server is running on http://localhost:${PORT}`);
  });
}

startServer().catch((err) => {
  console.error("Failed to start server", err);
});
